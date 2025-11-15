<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'code',
        'description',
    ];

    public function models()
    {
        return $this->hasMany(VehicleMaker::class, 'service_id');
    }

    public function posters()
    {
        return $this->hasMany(Poster::class, 'service_id');
    }

    public function posterCategories()
    {
        return $this->hasMany(PosterCategory::class, 'service_id');
    }
}
