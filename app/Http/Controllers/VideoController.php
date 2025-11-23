<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
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

    private function extractYoutubeId($url)
    {
        // If user enters only the ID
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        // Extract ID from full YouTube URL formats
        $pattern = '%(?:youtube\.com/(?:[^/\n\s]+/.+/|(?:v|e(?:mbed)?)?/|.*[?&]v=)|youtu\.be/)([A-Za-z0-9_-]{11})%';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null; // invalid
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Video::with([
            'categories',
            'services'
        ])
            ->select('id', 'title', 'youtube_id', 'active', 'created_at')
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        return view('videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::select('id', 'code', 'description')->get();
        $videoCategories = VideoCategory::get();
        return view('videos.add', compact(['services', 'videoCategories']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'youtube_url' => ['required', 'string', 'max:255'],
            'sequence' => ['nullable', 'integer', 'min:0'],
            'active' => ['nullable', 'integer', 'min:0'],
            'video_category_id'      => ['required', 'array'],              // must be an array
            'video_category_id.*'    => ['integer', 'exists:video_categories,id'],  // each item must be valid
            'service_id'      => ['required', 'array'],              // must be an array
            'service_id.*'    => ['integer', 'exists:services,id'],  // each item must be valid
        ]);

        // Extract YouTube ID
        $youtubeId = $this->extractYoutubeId($validated['youtube_url']);

        if (!$youtubeId) {
            return back()
                ->withErrors([
                    'youtube_url' => 'Invalid YouTube URL or ID.',
                ])
                ->withInput();
        }

        // Save to database
        $video = Video::create([
            'youtube_id' => $youtubeId,
            'youtube_url' => $validated['youtube_url'],
            'title' => $validated['title'],
            'sequence' => (int) $validated['sequence'],
            'active' => (int) $validated['active'],
        ]);

        $video->services()->sync($validated['service_id']);
        $video->categories()->sync($validated['video_category_id']);

        return redirect()->route('videos.index')->with('success', 'Video created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $video = Video::with(['services', 'categories'])->findOrFail($id);

        $services = Service::orderBy('description')->get();
        $videoCategories = VideoCategory::orderBy('name')->get();

        return view('videos.edit', compact(
            'video',
            'services',
            'videoCategories'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'youtube_url' => ['required', 'string', 'max:255'],
            'sequence' => ['nullable', 'integer', 'min:0'],
            'active' => ['nullable', 'integer', 'min:0'],
            'video_category_id' => ['required', 'array'],
            'video_category_id.*' => ['integer', 'exists:video_categories,id'],
            'service_id' => ['required', 'array'],
            'service_id.*' => ['integer', 'exists:services,id'],
        ]);

        $youtubeId = $this->extractYoutubeId($validated['youtube_url']);

        if (!$youtubeId) {
            return back()->withErrors([
                'youtube_url' => 'Invalid YouTube URL or ID.'
            ])->withInput();
        }

        $video = Video::findOrFail($id);

        $video->update([
            'youtube_id' => $youtubeId,
            'youtube_url' => $validated['youtube_url'],
            'title' => $validated['title'],
            'sequence' => $validated['sequence'],
            'active' => $validated['active'] ?? 0,
        ]);

        $video->services()->sync($validated['service_id']);
        $video->categories()->sync($validated['video_category_id']);

        return redirect()->route('videos.index')->with('success', 'Video updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $video = Video::findOrFail($id);

            // Delete related pivot records
            $video->services()->detach();
            $video->categories()->detach();

            // Delete video
            $video->delete();

            DB::commit();

            return redirect()
                ->route('videos.index')
                ->with('success', 'Video deleted successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
