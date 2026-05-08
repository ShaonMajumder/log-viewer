<?php

return [
    'route_prefix' => 'log-viewer',
    'route_name_prefix' => 'laravel.log.',
    'middleware' => ['web', 'auth'],

    // Return true to allow the current user.
    'authorize' => static function ($user): bool {
        return (bool) $user;
    },

    // The base layout used by the package view.
    'layout' => 'backend.layouts.app',

    // Full heading text shown on the viewer page.
    'heading' => 'প্রোডাকশন লগ',
];
