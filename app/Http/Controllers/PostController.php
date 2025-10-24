<?php

namespace App\Http\Controllers;

use App\Helpers\SettingHelper;
use App\Models\MediaFile;
use App\Models\Post;
use App\Models\PostHighlight;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locale = app()->getLocale();

        $posts = Post::with([
            'author:id,name',
            'currentTranslation' => function ($query) use ($locale) {
                $query->where('language_code', $locale)
                    ->select('post_id', 'title', 'summary', 'feature_image', 'translator_name');
            }
        ])
            ->select('id', 'author_id', 'source', 'status', 'is_special', 'published_at')
            ->paginate(15);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'feature_image' => ['sometimes', 'image', 'max:2048'],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'source' => ['nullable', 'string'],
            'translator_name' => ['nullable', 'string'],
            'published_at' => ['nullable', 'string'],
        ]);

        $publishedAt = null;
        if ($request->filled('published_at')) {
            try {
                $publishedAt = Carbon::createFromFormat('Y/m/d h:i A', $request->published_at);
            } catch (\Exception $e) {
                // If parsing fails, log it or handle gracefully
                \Log::warning('Invalid published_at format', ['input' => $request->published_at]);
            }
        }

        $settings = SettingHelper::getDefaultSettings();
        $cdnFilePath = null;
        $mediaFileData = [];

        $locale = app()->getLocale();

        // Save to database
        $post = Post::create([
            'author_id' => auth()->id(),
            'created_by' => auth()->id(),
            'status' => $settings->is_enable_post_approval ? 'pending' : 'approved',
            'source' => $request->source,
            'published_at' => $publishedAt,
            'is_special' => (int)$request->is_special,
        ]);

        if ($request->hasFile('feature_image')) {
            // Handle file upload
            $file = $request->file('feature_image');

            // Generate date-based folder
            $folder = now()->format('Y/m/d');

            // Send POST request to CDN API
            $response = Http::attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->withHeaders([
                'Authorization' => $settings->cdn_api_token,
                $settings->cdn_service_code_key => $settings->cdn_service_code_value,
            ])->post($settings->upload_api_url . '/api/upload/single?folder=' . $folder);

            if (!$response->successful() || !$response->json('success')) {
                \Log::error('CDN upload failed', ['response' => $response->body()]);
                return back()->withErrors(['feature_image' => 'Failed to upload image to CDN.']);
            }

            // $cdnUrl = $response->json('url');
            $cdnFilePath = $response->json('filePath');

            $mediaFileData = [
                'original_name' => $file->getClientOriginalName(),
                'file_name' => $response->json('filename'),
                'url' => $cdnFilePath,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'category' => 'post_gallery',
                'owner_type' => Post::class,
                'owner_id' => $post->id,
                'uploader_id' => auth()->id(),
            ];
        }

        $post->translations()->create([
            'language_code' => $locale,
            'title' => $validated['title'],
            'summary' => $validated['summary'],
            'content' => $validated['content'],
            'feature_image' => $cdnFilePath,
            'slug' => \Str::slug($validated['title']),
            'translator_name' => $request->translator_name,
        ]);

        if ($mediaFileData) {
            $mediaFileData['owner_id'] = $post->id;
            MediaFile::create($mediaFileData);
        }

        if ($request->has('is_special')) {
            $post->highlights()->create([
                'type' => 'special',
                'priority' => 1,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $locale = app()->getLocale();

        $post = Post::with([
            'author:id,name',
            'currentTranslation' => function ($query) use ($locale) {
                $query->where('language_code', $locale)
                    ->select('post_id', 'title', 'summary', 'content', 'feature_image', 'translator_name');
            }
        ])
            ->select('id', 'author_id', 'source', 'status', 'is_special', 'published_at')
            ->findOrFail($id);


        // For simplicity, merge translation attributes directly if available
        if ($post->currentTranslation) {
            $settings = SettingHelper::getDefaultSettings();

            $post->title = $post->currentTranslation->title;
            $post->summary = $post->currentTranslation->summary;
            $post->content = $post->currentTranslation->content ?? '';
            $post->feature_image = $post->currentTranslation->feature_image ? $settings->cdn_url . $post->currentTranslation->feature_image : '';
            $post->translator_name = $post->currentTranslation->translator_name;
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::with('translations')->findOrFail($id);

        $validated = $request->validate([
            'feature_image' => ['sometimes', 'image', 'max:2048'],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'source' => ['nullable', 'string'],
            'translator_name' => ['nullable', 'string'],
            'published_at' => ['nullable', 'string'],
        ]);

        $publishedAt = null;
        if ($request->filled('published_at')) {
            try {
                $publishedAt = Carbon::createFromFormat('Y/m/d h:i A', $request->published_at);
            } catch (\Exception $e) {
                // If parsing fails, log it or handle gracefully
                \Log::warning('Invalid published_at format', ['input' => $request->published_at]);
            }
        }

        $settings = SettingHelper::getDefaultSettings();
        $cdnFilePath = $post->currentTranslation->feature_image ?? null;

        // Handle new image upload if provided
        if ($request->hasFile('feature_image')) {
            $file = $request->file('feature_image');
            $folder = now()->format('Y/m/d');

            $response = Http::attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->withHeaders([
                'Authorization' => $settings->cdn_api_token,
                $settings->cdn_service_code_key => $settings->cdn_service_code_value,
            ])->post($settings->upload_api_url . '/api/upload/single?folder=' . $folder);

            if (!$response->successful() || !$response->json('success')) {
                \Log::error('CDN upload failed during update', ['response' => $response->body()]);
                return back()->withErrors(['feature_image' => 'Failed to upload image to CDN.']);
            }

            $cdnFilePath = $response->json('filePath');

            // Store media record
            MediaFile::create([
                'original_name' => $file->getClientOriginalName(),
                'file_name' => $response->json('filename'),
                'url' => $cdnFilePath,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'category' => 'post_gallery',
                'owner_type' => Post::class,
                'owner_id' => $post->id,
                'uploader_id' => auth()->id(),
            ]);
        }

        // Update main post fields
        $post->update([
            'status' => $settings->is_enable_post_approval ? 'pending' : 'approved',
            'source' => $request->source,
            'published_at' => $publishedAt,
            'is_special' => (int)$request->is_special,
        ]);

        // Determine current locale translation
        $locale = app()->getLocale();
        $translation = $post->translations()
            ->where('language_code', $locale)
            ->first();

        // Update or create translation
        if ($translation) {
            $translation->update([
                'title' => $validated['title'],
                'summary' => $validated['summary'],
                'content' => $validated['content'],
                'feature_image' => $cdnFilePath,
                'slug' => \Str::slug($validated['title']),
                'translator_name' => $request->translator_name,
            ]);
        } else {
            $post->translations()->create([
                'language_code' => $locale,
                'title' => $validated['title'],
                'summary' => $validated['summary'],
                'content' => $validated['content'],
                'feature_image' => $cdnFilePath,
                'slug' => \Str::slug($validated['title']),
                'translator_name' => $request->translator_name,
            ]);
        }

        if ($request->has('is_special')) {
            PostHighlight::updateOrCreate(
                [
                    'post_id' => $post->id,
                    'type' => 'special',
                ],
                [
                    'priority' => 1,
                    'created_by' => auth()->id(),
                ]
            );
        } else {
            // Remove highlight if unchecked
            PostHighlight::where('post_id', $post->id)
                ->where('type', 'special')
                ->delete();
        }

        return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }
}
