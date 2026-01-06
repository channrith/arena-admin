<?php

namespace App\Http\Controllers\Cars;

use App\Helpers\SettingHelper;
use App\Http\Controllers\Controller;
use App\Models\VehicleModel;
use App\Models\VehicleModelColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CarModelColorController extends Controller
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
    }

    public function edit(string $id)
    {
        $baseUrl = rtrim($this->settings->cdn_url ?? $this->settings->upload_api_url, '/') . '/';
        $vehicle = VehicleModel::with('maker:id,name,slug')->findOrFail($id);

        return view('cars.models.color', compact(['vehicle', 'baseUrl']));
    }

    public function update(Request $request, string $id)
    {
        $vehicle = VehicleModel::findOrFail($id);

        $colors = $request->colors;

        foreach ($colors['color_name'] as $index => $altText) {

            $colorId   = $colors['id'][$index];
            $file = $colors['image'][$index] ?? null;

            // If updating existing
            if ($colorId) {
                $color = VehicleModelColor::find($colorId);
            } else {
                $color = new VehicleModelColor();
                $color->model_id = $vehicle->id;
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

                $color->image_url = $response->json('filePath');
            }

            $color->color_name = $altText;
            $color->color_hex = $colors['color_hex'][$index] ?? '';

            $color->save();
        }

        return back()->with('success', 'Vehicle colors updated successfully!');
    }
}
