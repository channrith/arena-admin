<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VehicleSeries extends Model
{
    protected $fillable = [
        'maker_id',
        'type_id',
        'image_url',
        'name',
        'slug',
        'is_global_model',
        'is_local_model',
    ];

    public static function generateUniqueSlug(string $name, $makerId, $ignoreId = null)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        $query = static::where('maker_id', $makerId)->where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;

            $query = static::where('maker_id', $makerId)->where('slug', $slug);
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

    public function type()
    {
        return $this->belongsTo(VehicleType::class, 'type_id');
    }

    public function models()
    {
        return $this->hasMany(VehicleModel::class, 'series_id');
    }
}
