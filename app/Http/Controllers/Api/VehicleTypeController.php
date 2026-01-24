<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleTypeResource;
use App\Models\VehicleMaker;
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

        $makerSlug = $request->query('maker');
        $makerId = null;

        if ($makerSlug) {
            $maker = VehicleMaker::where('slug', $makerSlug)->first();

            if ($maker) {
                $makerId = $maker->id;
            }
        }

        $query = VehicleType::query()
            ->orderBy('sequence');

        if ($includeSeries) {
            $query->with(['series' => function ($q) use ($makerId) {
                $q->orderBy('name');

                if ($makerId) {
                    $q->where('maker_id', $makerId);
                }
            }]);
        }

        $types = $query->paginate($limit);

        return VehicleTypeResource::collection($types);
    }
}
