<?php

return [
    'route_prefix' => 'log-viewer',
    'route_name_prefix' => 'laravel.log.',
    'middleware' => ['web'],

    // The base layout used by the package view.
    'layout' => 'log-viewer::layouts.app',

    // Full heading text shown on the viewer page.
    'heading' => 'প্রোডাকশন লগ',
];
