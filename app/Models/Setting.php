<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public function preset()
    {
        return $this->belongsTo(Preset::class);
    }
}
