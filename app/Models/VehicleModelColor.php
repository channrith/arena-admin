<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModelColor extends Model
{
    protected $fillable = [
        'model_id',
        'color_name',
        'color_hex',
        'image_url',
    ];

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }
}
