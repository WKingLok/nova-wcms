<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Page Editor Premission
    |--------------------------------------------------------------------------
    |
    | 
    |
    */
    'acl' => [],


    /*
    |--------------------------------------------------------------------------
    | Page Editor Preview Config
    |--------------------------------------------------------------------------
    |
    | 
    |
    */
    'preview' => [
        'enabled' => env("PAGE_EDITOR_PREVIEW_ENABLED", false),
        'code_view' => env("PAGE_EDITOR_PREVIEW_CODE_VIEW", false),
    ],


    /*
    |--------------------------------------------------------------------------
    | Page Editor Tinymce Templates
    |--------------------------------------------------------------------------
    |
    | Set editor tinymce templates.
    |
    */
    'tinymce' => [
        'key' => env("TINYMCE_KEY"),
        'templates' => []
    ],

    /*
    |--------------------------------------------------------------------------
    | Page Editor Component List
    |--------------------------------------------------------------------------
    |
    | config component list
    |
    */
    "components" => [
        [
            'key' => "home_widgets",
            'label' => 'Main Widgets',
            'components' => [
                include('page-editor-components/example.php'),
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Page Editor Share Component List
    |--------------------------------------------------------------------------
    |
    | config share component list
    |
    */
    "share_components" => [
        include('page-editor-components/layout-header.php'),
        include('page-editor-components/layout-footer.php'),
    ]
];
