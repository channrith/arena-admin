<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleTypeResource;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 10);
        $includeSeries = in_array('series', explode(',', (string) $request->query('include')));

        $query = VehicleType::query()
            ->orderBy('sequence');

        if ($includeSeries) {
            $query->with(['series' => fn($q) => $q->orderBy('name')]);
        }

        $types = $query->paginate($limit);

        return VehicleTypeResource::collection($types);
    }
}
