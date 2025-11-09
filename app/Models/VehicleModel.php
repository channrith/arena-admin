<?php

namespace App\Models;

use App\Helpers\SettingHelper;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    protected $fillable = [
        'maker_id',
        'name',
        'slug',
        'is_global_model',
        'is_local_model',
        'year_of_production',
        'image_url',
        'thumbnail_image',
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

        if (!$this->image_url) {
            return null;
        }

        // Ensure no double slashes
        return rtrim($settings->cdn_url ?? $settings->upload_api_url, '/') . '/' . ltrim($this->image_url, '/');
    }

    public static function generateUniqueSlug(string $name, $ignoreId = null)
    {
        $baseSlug = \Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        $query = static::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
            $query = static::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }

    public function maker()
    {
        return $this->belongsTo(VehicleMaker::class, 'maker_id');
    }

    public function images()
    {
        return $this->hasMany(VehicleModelImage::class, 'model_id');
    }

    public function colors()
    {
        return $this->hasMany(VehicleModelColor::class, 'model_id');
    }

    public function specCategories()
    {
        return $this->hasMany(VehicleSpecCategory::class, 'model_id');
    }

    public function specs()
    {
        return $this->hasMany(VehicleSpec::class, 'model_id');
    }
}
