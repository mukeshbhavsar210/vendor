<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    function setting($key, $default = null) {
        $settings = Setting::first();

        return $settings?->{$key} ?? $default;
    }
}

?>