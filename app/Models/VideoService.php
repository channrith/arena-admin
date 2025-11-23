<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoService extends Model
{
    protected $table = 'video_service';

    protected $fillable = [
        'video_id',
        'service_id',
    ];

    public $timestamps = false;
}
