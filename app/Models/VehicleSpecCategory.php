<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleSpecCategory extends Model
{
    protected $fillable = [
        'model_id',
        'name',
        'name_kh',
        'sequence',
    ];

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    public function specs()
    {
        return $this->hasMany(VehicleSpec::class, 'category_id')->orderBy('sequence');
    }
}
