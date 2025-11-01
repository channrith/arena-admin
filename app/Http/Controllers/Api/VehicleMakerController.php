<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SettingHelper;
use App\Http\Controllers\Controller;
use App\Models\VehicleMaker;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class VehicleMakerController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->query('limit');

        $query = VehicleMaker::query()
            ->select('id', 'name', 'slug', 'logo_url', 'sequence')
            ->orderBy('sequence', 'asc')
            ->orderBy('name', 'asc');

        if ($limit && is_numeric($limit)) {
            $query->limit((int) $limit);
        }

        $makers = $query->get();

        return response()->json([
            'data' => $makers,
            'total' => $makers->count(),
        ]);
    }
}
