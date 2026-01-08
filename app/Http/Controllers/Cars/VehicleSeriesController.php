<?php

namespace App\Http\Controllers\Cars;

use App\Http\Controllers\Controller;
use App\Models\VehicleMaker;
use App\Models\VehicleSeries;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehicleSeriesController extends Controller
{
    public function index(Request $request)
    {
        $series = VehicleSeries::with(['maker', 'type'])
            ->when(
                $request->maker_id,
                fn($q) =>
                $q->where('maker_id', $request->maker_id)
            )
            ->when(
                $request->type_id,
                fn($q) =>
                $q->where('type_id', $request->type_id)
            )
            ->orderBy('maker_id')
            ->orderBy('type_id')
            ->orderBy('name')
            ->paginate(15);

        return view('cars.series.index', [
            'series' => $series,
            'makers' => VehicleMaker::orderBy('name')->get(),
            'types'  => VehicleType::orderBy('sequence')->get(),
        ]);
    }

    public function create()
    {
        return view('cars.series.add', [
            'makers' => VehicleMaker::orderBy('name')->get(),
            'types' => VehicleType::orderBy('sequence')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'maker_id' => ['required', 'exists:vehicle_makers,id'],
            'type_id'  => ['required', 'exists:vehicle_types,id'],
            'name'     => [
                'required',
                'string',
                'max:255',
                Rule::unique('vehicle_series')
                    ->where(
                        fn($q) => $q
                            ->where('maker_id', $request->maker_id)
                            ->where('type_id', $request->type_id)
                    ),
            ],
        ], [
            'name.unique' => 'This vehicle series already exists for the selected maker and type.',
        ]);

        $maker = VehicleMaker::findOrFail($validated['maker_id']);
        $type  = VehicleType::findOrFail($validated['type_id']);

        // Base slug: toyota-truck-tacoma
        $baseSlug = \Str::slug(
            $maker->name . ' ' . $type->name . ' ' . $validated['name']
        );

        $slug = $this->generateUniqueSlug($baseSlug);

        // Save to database
        VehicleSeries::create([
            'maker_id' => $maker->id,
            'type_id' => $type->id,
            'slug' => $slug,
            'name' => $validated['name'],
            'is_global_model' => (int) $request->is_global_model ?? 0,
            'is_local_model' => (int) $request->is_local_model ?? 0,
        ]);

        return redirect()->route('cars.series.index')->with('success', 'Item created successfully!');
    }

    public function edit(string $id)
    {
        $series = VehicleSeries::findOrFail($id);
        $makers = VehicleMaker::orderBy('name')->get();
        $types = VehicleType::orderBy('sequence')->get();

        return view('cars.series.edit', compact('series', 'makers', 'types'));
    }

    public function update(Request $request, string $id)
    {
        $series = VehicleSeries::findOrFail($id);

        $validated = $request->validate([
            'maker_id' => ['required', 'exists:vehicle_makers,id'],
            'type_id'  => ['required', 'exists:vehicle_types,id'],
            'name'     => [
                'required',
                'string',
                'max:255',
                Rule::unique('vehicle_series')
                    ->ignore($series->id)
                    ->where(
                        fn($q) => $q
                            ->where('maker_id', $request->maker_id)
                            ->where('type_id', $request->type_id)
                    ),
            ],
        ], [
            'name.unique' => 'This vehicle series already exists for the selected maker and type.',
        ]);

        $slugNeedsUpdate =
            $series->maker_id != $validated['maker_id'] ||
            $series->type_id  != $validated['type_id'] ||
            $series->name     != $validated['name'];

        if ($slugNeedsUpdate) {
            $maker = VehicleMaker::findOrFail($validated['maker_id']);
            $type  = VehicleType::findOrFail($validated['type_id']);

            $baseSlug = \Str::slug(
                $maker->name . ' ' . $type->name . ' ' . $validated['name']
            );

            $series->slug = $this->generateUniqueSlug($baseSlug, $series->id);
        }

        $series->is_global_model = (int) $request->is_global_model ?? 0;
        $series->is_local_model = (int) $request->is_local_model ?? 0;
        $series->update($validated);

        return redirect()->route('cars.series.index')->with('success', 'Item updated successfully!');
    }

    public function destroy(string $id)
    {
        $series = VehicleSeries::findOrFail($id);
        $series->delete();

        return redirect()->route('cars.series.index')->with('success', 'Item deleted successfully!');
    }

    /**
     * Ensure slug is unique
     */
    protected function generateUniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = $baseSlug;
        $counter = 1;

        while (VehicleSeries::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
