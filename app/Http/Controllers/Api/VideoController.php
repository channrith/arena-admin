<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $serviceId  = config('api.service_id');;
        $categorySlug = $request->category;

        $query = Video::where('active', 1)
            ->with(['services:id,description', 'categories:id,name'])
            ->select('id', 'title', 'youtube_url', 'youtube_id', 'sequence', 'active');

        // Filter by service
        if ($serviceId) {
            $query->whereHas('services', function ($q) use ($serviceId) {
                $q->where('services.id', $serviceId);
            });
        }

        // Filter by category
        if ($categorySlug) {
            $query->whereHas('categories', function ($q) use ($categorySlug) {
                $q->where('video_categories.slug', $categorySlug);
            });
        }

        $videos = $query->orderBy('sequence')->take(6)->get();

        return $videos;
    }
}
