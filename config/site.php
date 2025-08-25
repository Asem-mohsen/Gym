<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Scripts
    |--------------------------------------------------------------------------
    |
    | Define all JavaScript files needed for your site.
    |
    */

    'scripts' => [
        ['src' => 'assets/admin/plugins/global/plugins.bundle.js'],
        ['src' => 'assets/admin/js/scripts.bundle.js'],
        ['src' => 'assets/admin/js/custom.js'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Styles
    |--------------------------------------------------------------------------
    |
    | Define default and directional (LTR/RTL) CSS files.
    |
    */

    'styles' => [
        'default' => [
            'assets/plugins/fontawesome-free/css/all.min.css',
            'assets/admin/plugins/global/plugins.bundle.css',
            'assets/admin/css/style.bundle.css',
            'assets/admin/css/custom-style.css',
            'assets/admin/css/toastr.min.css',
        ],

        'directions' => [
            'ltr' => [],
            'rtl' => [],
        ],
    ],

];
