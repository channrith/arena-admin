<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poster extends Model
{
    protected $table = 'posters';

    protected $fillable = [
        'title',
        'sequence',
        'image_url',
        'thumbnail_image',
        'url',
        'service_id',
        'category_id',
    ];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(PosterCategory::class, 'category_id');
    }
}
