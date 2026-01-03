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
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
            'name'     => 'required|string|max:100|unique:vehicle_types,name',
            'sequence' => 'required|integer|min:0',
        ]);

        $service = Service::where('code', 'acauto')->first();

        // Save to database
        VehicleType::create([
            'service_id' => $service ? $service->id : null,
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
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('vehicle_types', 'name')->ignore($vehicleType->id),
            ],
            'sequence' => 'required|integer|min:0',
        ]);

        $vehicleType->update([
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
