<?php

namespace App\Helpers;

use App\Models\Setting;

class Settings
{
    public static function get($key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }
        switch ($setting->type) {
            case 'boolean':
            case 'bool':
                return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
            case 'int':
            case 'integer':
                return (int) $setting->value;
            case 'float':
            case 'double':
                return (float) $setting->value;
            case 'array':
                return explode(',', $setting->value);
            case 'json':
                return json_decode($setting->value, true);
            case 'string':
            default:
                return $setting->value;
        }
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $type = 'string', $group = 'general')
    {
        $setting = Setting::firstOrNew(['key' => $key]);
        $setting->value = $value;
        $setting->type = $type;
        $setting->group = $group;
        $setting->save();
        return $setting;
    }
}
