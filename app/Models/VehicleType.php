<?php

namespace App\Models;

use App\Helpers\SettingHelper;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $fillable = [
        'service_id',
        'name',
        'slug',
        'sequence',
        'icon_url',
    ];

    protected $appends = ['feature_image_url'];

    public function getFeatureImageUrlAttribute()
    {
        $settings = SettingHelper::getDefaultSettings();

        if (!$this->icon_url) {
            return null;
        }

        // Ensure no double slashes
        return rtrim($settings->cdn_url ?? $settings->upload_api_url, '/') . '/' . ltrim($this->icon_url, '/');
    }

    public function series()
    {
        return $this->hasMany(VehicleSeries::class, 'type_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
