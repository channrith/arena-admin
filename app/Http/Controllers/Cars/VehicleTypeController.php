<?php

namespace App\Http\Controllers\Cars;

use App\Helpers\SettingHelper;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class VehicleTypeController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();

        $vehicles = VehicleType::orderBy('created_at', 'DESC')->paginate(15);

        return view('cars.types.index', compact('vehicles'));
    }

    public function create()
    {
        return view('cars.types.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'icon_url' => 'sometimes|image|max:2048',
            'name'     => 'required|string|max:100|unique:vehicle_types,name',
            'sequence' => 'required|integer|min:0',
        ]);

        $settings = SettingHelper::getDefaultSettings();
        $iconCdnFilePath = null;
        $service = Service::where('code', 'acauto')->first();

        if ($request->hasFile('icon_url')) {
            $file = $request->file('icon_url');
            $folder = 'acauto/type';

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
                return back()->withErrors(['icon_url' => 'Failed to upload icon to CDN.']);
            }

            // $cdnUrl = $response->json('url');
            $iconCdnFilePath = $response->json('filePath');
        }

        // Save to database
        VehicleType::create([
            'service_id' => $service ? $service->id : null,
            'icon_url' => $iconCdnFilePath,
            'slug' => \Str::slug($validated['name']),
            'name' => $validated['name'],
            'sequence' => $validated['sequence'],
        ]);

        return redirect()->route('cars.types.index')->with('success', 'Item created successfully!');
    }

    public function edit(string $id)
    {
        $vehicleType = VehicleType::with('service')->findOrFail($id);

        return view('cars.types.edit', compact('vehicleType'));
    }

    public function update(Request $request, string $id)
    {
        $vehicleType = VehicleType::with('service')->findOrFail($id);

        $validated = $request->validate([
            'icon_url' => ['sometimes', 'image', 'max:2048'],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('vehicle_types', 'name')->ignore($vehicleType->id),
            ],
            'sequence' => 'required|integer|min:0',
        ]);

        $settings = SettingHelper::getDefaultSettings();
        $iconCdnFilePath = $vehicleType->icon_url ?? null;

        if ($request->hasFile('icon_url')) {
            $file = $request->file('icon_url');
            $folder = 'acauto/type';

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
                return back()->withErrors(['icon_url' => 'Failed to upload icon to CDN.']);
            }

            // $cdnUrl = $response->json('url');
            $iconCdnFilePath = $response->json('filePath');
        }

        $vehicleType->update([
            'icon_url' => $iconCdnFilePath,
            'slug' => \Str::slug($validated['name']),
            'name' => $validated['name'],
            'sequence' => $validated['sequence'],
        ]);

        return redirect()->route('cars.types.index')->with('success', 'Item updated successfully!');
    }

    public function destroy(string $id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        $vehicleType->delete();

        return redirect()->route('cars.types.index')->with('success', 'Item deleted successfully!');
    }
}
