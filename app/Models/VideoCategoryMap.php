<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoCategoryMap extends Model
{
    protected $table = 'video_category_map';

    protected $fillable = [
        'video_id',
        'video_category_id',
    ];

    public $timestamps = false;
}
