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
        $limit = (int) $request->query('limit', 3);
        $includeSeries = in_array('series', explode(',', (string) $request->query('include')));

        $isGlobal = $request->query('is_global_model');

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
            $query->with(['series' => function ($q) use ($makerId, $isGlobal) {
                $q->orderBy('name');

                if ($makerId) {
                    $q->where('maker_id', $makerId);
                }

                if ($isGlobal) {
                    $q->where('is_global_model', (int) $isGlobal);
                } else {
                    $q->where('is_local_model', 1);
                }
            }]);
        }

        $types = $query->paginate($limit);

        return VehicleTypeResource::collection($types);
    }
}
