<?php

namespace App\Models;

use App\Helpers\SettingHelper;
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

    public function getFeatureImageAttribute($value)
    {
        $settings = SettingHelper::getDefaultSettings();

        if (!$value) {
            return null;
        }

        // Ensure no double slashes
        return rtrim($settings->cdn_url ?? $settings->upload_api_url, '/') . '/' . ltrim($value, '/');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
