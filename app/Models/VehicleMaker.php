<?php

namespace App\Models;

use App\Helpers\SettingHelper;
use Illuminate\Database\Eloquent\Model;

class VehicleMaker extends Model
{
    protected $fillable = [
        'sequence',
        'name',
        'slug',
        'logo_url',
    ];

    protected $appends = ['image_url']; // include in JSON output


    public function getImageUrlAttribute()
    {
        $settings = SettingHelper::getDefaultSettings();

        if (!$this->logo_url) {
            return null;
        }

        // Ensure no double slashes
        return rtrim($settings->cdn_url ?? $settings->upload_api_url, '/') . '/' . ltrim($this->logo_url, '/');
    }

    public function models()
    {
        return $this->hasMany(VehicleModel::class, 'maker_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
