<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PosterCategory;
use Illuminate\Http\Request;

class PosterController extends Controller
{
    public function index(Request $request)
    {
        $categories = PosterCategory::with([
            'posters' => function ($q) {
                $q->orderBy('sequence');
            }
        ])
            ->orderBy('id')
            ->get();

        $result = [];

        foreach ($categories as $category) {
            $result[$category->remark] = $category->posters;
        }

        return response()->json($result);
    }
}
