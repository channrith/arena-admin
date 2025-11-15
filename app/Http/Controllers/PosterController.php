<?php

namespace App\Http\Controllers;

use App\Helpers\SettingHelper;
use App\Models\Post;
use App\Models\Poster;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PosterController extends Controller
{
    private $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->service = Service::where('code', 'acauto')->first();
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locale = app()->getLocale();

        $posters = Poster::with(['category'])->paginate(15);

        return view('posters.index', compact('posters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->service->posterCategories;
        return view('posters.add', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'sequence' => 'nullable|integer|min:0',
            'image_url' => 'nullable|image|max:2048',
            'category_id' => 'required|integer|exists:poster_categories,id',
        ]);

        $settings = SettingHelper::getDefaultSettings();
        $cdnFilePath = null;

        if ($request->hasFile('image_url')) {
            // Handle file upload
            $file = $request->file('image_url');

            // Generate date-based folder
            $folder = 'acauto/poster';

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
                return back()->withErrors(['image_url' => 'Failed to upload image to CDN.']);
            }

            // $cdnUrl = $response->json('url');
            $cdnFilePath = $response->json('filePath');
        }

        Poster::create([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'sequence' => (int) $validated['sequence'],
            'category_id' => (int) $validated['category_id'],
            'service_id' => $this->service->id,
            'image_url' => $cdnFilePath,
        ]);

        return redirect()->route('posters.index')->with('success', 'Poster created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = $this->service->posterCategories;
        $poster = Poster::with(['category'])->findOrFail($id);

        return view('posters.edit', compact(['categories', 'poster']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $poster = Poster::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'sequence' => 'nullable|integer|min:0',
            'image_url' => 'nullable|image|max:2048',
            'category_id' => 'required|integer|exists:poster_categories,id',
        ]);

        $settings = SettingHelper::getDefaultSettings();
        $cdnFilePath = $poster->image_url ?? null;

        // Handle new image upload if provided
        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $folder = 'acauto/poster';

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
                return back()->withErrors(['image_url' => 'Failed to upload image to CDN.']);
            }

            $cdnFilePath = $response->json('filePath');
        }

        // Update main post fields
        $poster->update([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'sequence' => (int) $validated['sequence'],
            'category_id' => (int) $validated['category_id'],
            'service_id' => $this->service->id,
            'image_url' => $cdnFilePath,
        ]);

        return redirect()->route('posters.index')->with('success', 'Poster updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $poster = Poster::findOrFail($id);
        $poster->delete();

        return redirect()->route('posters.index')->with('success', 'Poster deleted successfully!');
    }
}
