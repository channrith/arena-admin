<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';

    protected $fillable = [
        'title',
        'youtube_url',
        'youtube_id',
        'active',
        'sequence',
    ];

    public $timestamps = true;

    public function categories()
    {
        return $this->belongsToMany(VideoCategory::class, 'video_category_map', 'video_id', 'video_category_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'video_service', 'video_id', 'service_id');
    }
}
