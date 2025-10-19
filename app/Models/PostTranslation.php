<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    protected $fillable = [
        'post_id',
        'language_code',
        'title',
        'slug',
        'summary',
        'content',
        'feature_image',
        'thumbnail_image',
        'translator_name'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
