<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingHelper
{
    public static function getDefaultSettings()
    {
        return (object) Setting::where('is_active', 1)
            ->whereHas('preset', fn($q) => $q->where('is_default', 1))
            ->pluck('setting_value', 'setting_key')
            ->toArray();
    }
}
