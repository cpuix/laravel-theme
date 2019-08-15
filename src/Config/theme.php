<?php

return [
    // Current Theme
    'current_theme' => 'default',

    /*
     * Theme Folder Names
     */
    'views_folder' => [
        'layout'    => 'layouts',
        'component' => 'components',
    ],
    /*
     * Theme Blade File Names
     */
    'views_blade' => [
        'index'     => 'index',
        'header'    => 'header',
        'footer'    => 'footer',
        'layout'    => 'main',
    ],
    /*
     * Laravel Default Js & Sass Folder & File Name
     */
    'webpack' => [
        'folder' => [
            'js'    => 'js',
            'css'   => 'sass',
        ],
        'file' => [
            'css'           => 'app.scss',
            'variable'      => '_variables.scss',
            'js'            => 'app.js',
            'bootstrap'     => 'bootstrap.js',
        ]
    ],

    /*
     |--------------------------------------------------------------------------
     | Theme Settings
     |--------------------------------------------------------------------------
     |
     |
     */
    'resource_path'     => 'themes',
    'public_path'       => 'themes',

];
