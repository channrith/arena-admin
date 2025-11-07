<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SettingHelper;
use App\Http\Controllers\Controller;
use App\Models\VehicleMaker;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class CarModelController extends Controller
{
    protected $settings;
    public function __construct()
    {
        $settings = SettingHelper::getDefaultSettings();
        $this->settings = $settings;
    }

    public function indexByMaker(Request $request, $maker)
    {
        $makerRecord = VehicleMaker::where('slug', $maker)->first();

        if (!$makerRecord) {
            return response()->json(['message' => 'Vehicle maker not found'], 404);
        }

        $isGlobal = $request->query('is_global_model');

        $query = VehicleModel::where('maker_id', $makerRecord->id)
            ->with('maker:id,name,slug')->orderBy('slug');

        // Apply filter if provided
        if ($isGlobal !== null) {
            $query->where('is_global_model', (int) $isGlobal);
        }

        // Paginate results (default 10 per page)
        $models = $query->orderBy('name')
            ->paginate($request->get('per_page', 15));

        return response()->json($models);
    }

    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (empty($query)) {
            return response()->json(['message' => 'Search query required'], 400);
        }

        // Search vehicles by name or partial match
        $vehicles = VehicleModel::with([
            'maker:id,name,slug',
            'specCategories.specs' // eager load categories + specs
        ])
            ->where('name', 'like', "%{$query}%")
            ->orWhereHas('maker', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->limit(5) // limit results for speed
            ->get();

        // Transform to expected output format
        $results = $vehicles->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'name' => $vehicle->name,
                'image' => rtrim($this->settings->cdn_url ?? $this->settings->upload_api_url, '/') . '/' . ltrim($vehicle->thumbnail_image ?? $vehicle->image_url, '/'),
                'options' => $vehicle->specCategories->map(function ($category) {
                    return [
                        'category' => trim($category->name . ' ' . ($category->name_kh ?? '')),
                        'specs' => $category->specs->map(function ($spec) {
                            return [
                                'label' => trim($spec->label . ' ' . ($spec->label_kh ?? '')),
                                'value' => $spec->value,
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json($results);
    }

    public function getModelSpecs($modelId)
    {
        $model = VehicleModel::with([
            'colors',
            'images',
            'specCategories.specs'
        ])
            ->findOrFail($modelId);

        $response = [
            'name' => $model->name,
            'image' => rtrim($this->settings->cdn_url ?? $this->settings->upload_api_url, '/') . '/' . ltrim($model->image_url, '/'),
            'features' => [
                'colors' => $model->colors->map(function ($color) {
                    return [
                        'name' => $color->color_name,
                        'code' => $color->color_hex,
                        'image' => rtrim($this->settings->cdn_url ?? $this->settings->upload_api_url, '/') . '/' . ltrim($color->image_url, '/'),
                    ];
                })->values(),
                'images' => $model->images->map(function ($image) {
                    return [
                        'alt' => $image->alt_text,
                        'url' => rtrim($this->settings->cdn_url ?? $this->settings->upload_api_url, '/') . '/' . ltrim($image->image_url, '/'),
                    ];
                })->values(),
            ],
            'options' => $model->specCategories->map(function ($category) {
                return [
                    'category' => trim($category->name . ' ' . ($category->name_kh ?? '')),
                    'specs' => $category->specs->map(function ($spec) {
                        return [
                            'label' => trim($spec->label . ' ' . ($spec->label_kh ?? '')),
                            'value' => $spec->value,
                        ];
                    })->values(),
                ];
            })->values(),
        ];

        return response()->json($response);
    }
}
