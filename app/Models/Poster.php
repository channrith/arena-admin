<?php

namespace App\Models;

use App\Helpers\SettingHelper;
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

        if (!$this->image_url) {
            return null;
        }

        // Ensure no double slashes
        return rtrim($settings->cdn_url ?? $settings->upload_api_url, '/') . '/' . ltrim($this->image_url, '/');
    }

    public function category()
    {
        return $this->belongsTo(PosterCategory::class, 'category_id');
    }
}
