<?php

namespace App\Models;

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

    public function series()
    {
        return $this->hasMany(VehicleSeries::class, 'type_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
