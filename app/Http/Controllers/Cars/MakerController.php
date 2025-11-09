<?php

namespace App\Http\Controllers\Cars;

use App\Helpers\SettingHelper;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\VehicleMaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MakerController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();

        $vehicles = VehicleMaker::paginate(15);

        return view('cars.makers.index', compact('vehicles'));
    }

    public function create()
    {
        return view('cars.makers.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'logo_url' => ['sometimes', 'image', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'sequence' => ['required', 'integer', 'min:0'],
        ]);

        $settings = SettingHelper::getDefaultSettings();
        $cdnFilePath = null;
        $service = Service::where('code', 'acauto')->first();

        if ($request->hasFile('logo_url')) {
            $file = $request->file('logo_url');
            $folder = 'acauto/maker';

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
                return back()->withErrors(['logo_url' => 'Failed to upload image to CDN.']);
            }

            // $cdnUrl = $response->json('url');
            $cdnFilePath = $response->json('filePath');
        }

        // Save to database
        VehicleMaker::create([
            'service_id' => $service ? $service->id : null,
            'slug' => \Str::slug($validated['name']),
            'name' => $validated['name'],
            'sequence' => $validated['sequence'],
            'logo_url' => $cdnFilePath,
        ]);

        return redirect()->route('cars.makers.index')->with('success', 'Item created successfully!');
    }

    public function edit(string $id)
    {
        $vehicleMaker = VehicleMaker::with('service')->findOrFail($id);

        return view('cars.makers.edit', compact('vehicleMaker'));
    }

    public function update(Request $request, string $id)
    {
        $vehicleMaker = VehicleMaker::with('service')->findOrFail($id);

        $validated = $request->validate([
            'logo_url' => ['sometimes', 'image', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'sequence' => ['required', 'integer', 'min:0'],
        ]);

        $settings = SettingHelper::getDefaultSettings();
        $cdnFilePath = $vehicleMaker->logo_url ?? null;

        // Handle new image upload if provided
        if ($request->hasFile('logo_url')) {
            $file = $request->file('logo_url');
            $folder = 'acauto/maker';

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
                return back()->withErrors(['logo_url' => 'Failed to upload image to CDN.']);
            }

            $cdnFilePath = $response->json('filePath');
        }

        $vehicleMaker->update([
            'slug' => \Str::slug($validated['name']),
            'name' => $validated['name'],
            'sequence' => $validated['sequence'],
            'logo_url' => $cdnFilePath,
        ]);

        return redirect()->route('cars.makers.index')->with('success', 'Item updated successfully!');
    }

    public function destroy(string $id)
    {
        $vehicleMaker = VehicleMaker::findOrFail($id);
        $vehicleMaker->delete();

        return redirect()->route('cars.makers.index')->with('success', 'Item deleted successfully!');
    }
}
