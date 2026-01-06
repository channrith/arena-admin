<?php

namespace App\Http\Controllers\Cars;

use App\Helpers\SettingHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\VehicleMaker;
use App\Models\VehicleModel;
use App\Models\VehicleSpec;
use App\Models\VehicleSpecCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarModelController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();

        $vehicles = VehicleModel::with([
            'maker:id,name,slug',
        ])->orderBy('id', 'desc')->paginate(15);

        return view('cars.models.index', compact('vehicles'));
    }

    public function create()
    {
        $categories = config('constant.CAR_SPEC_CATEGORIES');
        $makers = VehicleMaker::all();
        return view('cars.models.add', compact(['categories', 'makers']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image_url' => ['sometimes', 'image', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'year_of_production' => ['required', 'integer'],
            'maker_id' => ['required', 'integer'],
            'year_of_production' => ['nullable'],
        ]);

        $categories = config('constant.CAR_SPEC_CATEGORIES');
        $slug = VehicleModel::generateUniqueSlug($validated['name']);
        $settings = SettingHelper::getDefaultSettings();
        $cdnFilePath = null;

        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $folder = 'acauto/' . now()->format('Y/m/d');

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

        DB::beginTransaction();

        try {
            // Save to database
            $model = VehicleModel::create([
                'maker_id' => $validated['maker_id'],
                'slug' => $slug,
                'name' => $validated['name'],
                'year_of_production' => $validated['year_of_production'],
                'image_url' => $cdnFilePath,
                'is_global_model' => (int) $request->is_global_model ?? 0,
                'is_local_model' => (int) $request->is_local_model ?? 0,
                'year_of_production' => $validated['year_of_production'],
            ]);

            // Prepare bulk specs data
            $specRecords = [];

            foreach ($categories as $category) {

                $newCategory = VehicleSpecCategory::create([
                    'model_id' => $model->id,
                    'name' => $category['name'] ?? 'Unknown',
                    'name_kh' => $category['name_kh'] ?? 'Unknown',
                    'sequence' => $category['sequence'] ?? 1,
                ]);

                foreach ($request->input('specs', []) as $categoryId => $specList) {
                    if ($category['id'] == $categoryId) {
                        foreach ($specList as $specData) {
                            if (!empty($specData['label']) || !empty($specData['value'])) {
                                $specRecords[] = [
                                    'model_id'    => $model->id,
                                    'category_id' => $newCategory->id,
                                    'label'       => $specData['label'] ?? null,
                                    'label_kh'    => $specData['label_kh'] ?? null,
                                    'value'       => $specData['value'] ?? null,
                                    'sequence'    => $specData['sequence'] ?? 1,
                                ];
                            }
                        }
                    }
                }
            }

            // Insert all specs in one query
            if (!empty($specRecords)) {
                VehicleSpec::insert($specRecords);
            }

            DB::commit();

            return redirect()->route('cars.models.index')->with('success', 'Item created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(string $id)
    {
        $makers = VehicleMaker::all();
        $vehicle = VehicleModel::with('maker:id,name,slug')->findOrFail($id);
        $categories = VehicleSpecCategory::where('model_id', $vehicle->id)
            ->with(['specs'])
            ->orderBy('sequence')
            ->get();

        return view('cars.models.edit', compact(['categories', 'vehicle', 'makers']));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'image_url' => ['sometimes', 'image', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'year_of_production' => ['required', 'integer'],
            'maker_id' => ['required', 'integer'],
            'year_of_production' => ['nullable'],
        ]);

        $model = VehicleModel::findOrFail($id);
        $slug = VehicleModel::generateUniqueSlug($validated['name'], $model->id);
        $settings = SettingHelper::getDefaultSettings();
        $cdnFilePath = $model->image_url ?? null;

        // Handle new image upload if provided
        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $folder = 'acauto/' . now()->format('Y/m/d');

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

        DB::beginTransaction();

        try {
            $model->update([
                'maker_id' => $validated['maker_id'],
                'slug' => $slug,
                'name' => $validated['name'],
                'year_of_production' => $validated['year_of_production'],
                'image_url' => $cdnFilePath,
                'is_global_model' => (int) $request->is_global_model ?? 0,
                'is_local_model' => (int) $request->is_local_model ?? 0,
                'year_of_production' => $validated['year_of_production'],
            ]);

            $oldSpecIds = VehicleSpec::where('model_id', $model->id)->pluck('id')->toArray();

            $existingIds = [];
            $newSpecs = [];

            foreach ($request->input('specs', []) as $categoryId => $specList) {
                foreach ($specList as $specData) {
                    // Update existing specs (have ID)
                    if (!empty($specData['id'])) {
                        $existingIds[] = $specData['id'];
                        VehicleSpec::where('id', $specData['id'])
                            ->update([
                                'label' => $specData['label'] ?? null,
                                'label_kh' => $specData['label_kh'] ?? null,
                                'value' => $specData['value'] ?? null,
                                'sequence' => $specData['sequence'] ?? 1,
                            ]);
                    }
                    // Prepare new specs (no ID)
                    else {
                        if (!empty($specData['label']) || !empty($specData['value'])) {
                            $newSpecs[] = [
                                'model_id' => $model->id,
                                'category_id' => $categoryId,
                                'label' => $specData['label'] ?? null,
                                'label_kh' => $specData['label_kh'] ?? null,
                                'value' => $specData['value'] ?? null,
                                'sequence' => $specData['sequence'] ?? 1,
                            ];
                        }
                    }
                }
            }

            // Delete removed specs
            $deletedIds = array_diff($oldSpecIds, $existingIds);
            if (!empty($deletedIds)) {
                VehicleSpec::whereIn('id', $deletedIds)->delete();
            }

            // Bulk insert new specs
            if (!empty($newSpecs)) {
                VehicleSpec::insert($newSpecs);
            }

            DB::commit();

            return redirect()->route('cars.models.index')->with('success', 'Item updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $model = VehicleModel::findOrFail($id);

            // Delete all related specs first (through categories or directly)
            VehicleSpec::where('model_id', $model->id)->delete();

            // Delete all related spec categories
            VehicleSpecCategory::where('model_id', $model->id)->delete();

            // Finally delete the vehicle model
            $model->delete();

            DB::commit();

            return redirect()
                ->route('cars.models.index')
                ->with('success', 'Item deleted successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
