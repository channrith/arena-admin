<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoCategory extends Model
{
    protected $table = 'video_categories';

    protected $fillable = [
        'id',
        'name',
        'slug',
    ];

    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'int';

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'video_category_map', 'video_category_id', 'video_id');
    }
}
