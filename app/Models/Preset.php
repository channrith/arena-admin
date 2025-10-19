<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Preset extends Model
{
    /**
     * Get the settings per preset.
     */
    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', 1);
    }
}
