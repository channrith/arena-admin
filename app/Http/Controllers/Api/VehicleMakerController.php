<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VehicleMaker;
use Illuminate\Http\Request;

class VehicleMakerController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->query('limit');

        $query = VehicleMaker::query()
            ->select(
                'id',
                'name',
                'slug',
                'logo_url',
                'banner_url',
                'description',
                'sequence'
            )
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

    public function showBySlug($slug)
    {
        $maker = VehicleMaker::select(
            'id',
            'name',
            'slug',
            'logo_url',
            'banner_url',
            'description',
            'sequence'
        )
            ->where('slug', $slug)
            ->first();

        if (!$maker) {
            return response()->json([
                'message' => 'Vehicle maker not found.'
            ], 404);
        }

        return response()->json($maker);
    }
}
