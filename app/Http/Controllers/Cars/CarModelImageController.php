<?php

namespace App\Http\Controllers\Cars;

use App\Helpers\SettingHelper;
use App\Http\Controllers\Controller;
use App\Models\VehicleModel;
use App\Models\VehicleModelImage;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class CarModelImageController extends Controller
{
    protected $settings;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $settings = SettingHelper::getDefaultSettings();
        $this->settings = $settings;

        $this->middleware('auth');
    }

    public function edit(string $id)
    {
        $baseUrl = rtrim($this->settings->cdn_url ?? $this->settings->upload_api_url, '/') . '/';
        $vehicle = VehicleModel::with('maker:id,name,slug')->findOrFail($id);

        return view('cars.models.image', compact(['vehicle', 'baseUrl']));
    }

    public function update(Request $request, string $id)
    {
        $vehicle = VehicleModel::findOrFail($id);

        $images = $request->images;

        foreach ($images['alt_text'] as $index => $altText) {

            $imageId   = $images['id'][$index];
            $file = $images['image'][$index] ?? null;

            // If updating existing
            if ($imageId) {
                $image = VehicleModelImage::find($imageId);
            } else {
                $image = new VehicleModelImage();
                $image->model_id = $vehicle->id;
            }

            // Save image if uploaded
            if ($file) {
                $folder = 'acauto/' . now()->format('Y/m/d');

                $response = Http::attach(
                    'file',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )->withHeaders([
                    'Authorization' => $this->settings->cdn_api_token,
                    $this->settings->cdn_service_code_key => $this->settings->cdn_service_code_value,
                ])->post($this->settings->upload_api_url . '/api/upload/single?folder=' . $folder);

                if (!$response->successful() || !$response->json('success')) {
                    \Log::error('CDN upload failed during update', ['response' => $response->body()]);
                    return back()->withErrors(['image_url' => 'Failed to upload image to CDN.']);
                }

                $image->image_url = $response->json('filePath');
            }

            $image->alt_text = $altText;
            $image->sequence = $images['sequence'][$index] ?? 0;

            $image->save();
        }

        return back()->with('success', 'Vehicle images updated successfully!');
    }
}
