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

    protected $appends = ['feature_image_url', 'thumbnail_image_url']; // include in JSON output

    public function getThumbnailImageUrlAttribute()
    {
        $settings = SettingHelper::getDefaultSettings();

        if (!$this->thumbnail_image) {
            return null;
        }

        // Ensure no double slashes
        return rtrim($settings->cdn_url ?? $settings->upload_api_url, '/') . '/' . ltrim($this->thumbnail_image, '/');
    }

    public function getFeatureImageUrlAttribute()
    {
        $settings = SettingHelper::getDefaultSettings();

        if (!$this->feature_image) {
            return null;
        }

        // Ensure no double slashes
        return rtrim($settings->cdn_url ?? $settings->upload_api_url, '/') . '/' . ltrim($this->feature_image, '/');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
