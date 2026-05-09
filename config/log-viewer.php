<?php

return [
    'route_prefix' => 'log-viewer',
    'route_name_prefix' => 'laravel.log.',
    'middleware' => ['web'],

    // Guard used to resolve authenticated user context.
    'auth_guard' => 'web',

    // Require authenticated users before viewing logs.
    'auth_required' => false,

    // Optional email allow-list. Empty means no email restriction.
    'allowed_emails' => [],

    // How to handle unauthorized access: 'abort' (403) or 'redirect'.
    'unauthorized_action' => 'abort',

    // Redirect target when unauthorized_action is 'redirect'.
    'unauthorized_redirect_to' => '/',

    // Return true to allow the current user.
    'authorize' => static function ($user): bool {
        return true;
    },

    // The base layout used by the package view.
    'layout' => 'log-viewer::layouts.app',

    // Full heading text shown on the viewer page.
    'heading' => 'প্রোডাকশন লগ',
];
