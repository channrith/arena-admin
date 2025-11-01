<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModelColor extends Model
{
    protected $fillable = [
        'model_id',
        'alt_text',
        'image_url',
        'sequence',
    ];

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }
}
