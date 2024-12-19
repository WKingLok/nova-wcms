<?php

return [
    'menu' => [
        [
            'title' => "Page",
            'icon' => 'desktop-computer',
            'collapsable' => true,
            'items' => [
                [
                    'class' => 'Packages\PageEditor\Nova\PageEditor',
                    'type' => 'resource',
                ],
                [
                    'class' => 'Packages\PageEditor\Nova\ShareWidget',
                    'type' => 'resource',
                ],
                [
                    'class' => 'Packages\HeaderMenu\Nova\HeaderMenu',
                    'type' => 'resource',
                ],
            ]
        ],
        [
            'title' => "Admin",
            'icon' => 'adjustments',
            'collapsable' => true,
            'items' => [
                [
                    'class' => 'Packages\Basic\Nova\Administrator',
                    'type' => 'resource',
                ],
            ]
        ],
    ],
    'role' => [
        'administrators' => [
            'SuperAdmin'
        ]
    ],
    'permission' => [
        'administrators' => [
            [
                'key' => 'page_editor',
                'label' => 'Page Editor'
            ],
            [
                'key' => 'example',
                'label' => 'Example'
            ],
        ]
    ]
];
