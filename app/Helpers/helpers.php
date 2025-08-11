<?php

use App\Helpers\RoleDataHelper;
use App\Helpers\Settings;

if (!function_exists('setting')) {
     function setting($key, $default = null)
     {
          return Settings::get($key, $default);
     }
}