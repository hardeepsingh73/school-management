<?php

use App\Helpers\RoleDataHelper;
use App\Helpers\Settings;

if (!function_exists('setting')) {
    /**
     * Global helper function to retrieve application settings.
     *
     * This function acts as a shortcut for fetching configuration values
     * stored by the application (via App\Helpers\Settings).
     *
     * Example:
     *   setting('site_name')           → returns the 'site_name' setting value
     *   setting('timezone', 'UTC')     → returns the setting or 'UTC' if not found
     *
     * @param string $key      The setting key/name to retrieve.
     * @param mixed  $default  The default value to return if the key doesn't exist.
     *
     * @return mixed           The setting value or the default if not found.
     */
    function setting($key, $default = null)
    {
        // Call the Settings helper to get the specified setting value.
        return Settings::get($key, $default);
    }
}
