<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleSpec extends Model
{
    protected $fillable = [
        'category_id',
        'model_id',
        'label',
        'label_kh',
        'value',
        'sequence',
    ];

    public function category()
    {
        return $this->belongsTo(VehicleSpecCategory::class, 'category_id');
    }

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }
}
