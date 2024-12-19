<?php

return [
    'menu' => [
        [
            'class' => 'App\Nova\Example',
            'type' => 'resource',
        ],
        [
            'class' => 'App\Nova\Photograph',
            'type' => 'resource',
        ],
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
            'SuperAdmin',
            'Editor',
            'Approver',
        ]
    ],
    'permission' => [
        'administrators' => [
            [
                'key' => 'page',
                'label' => 'Page'
            ],
            [
                'key' => 'menu',
                'label' => 'Menu'
            ],
            [
                'key' => 'example',
                'label' => 'Example'
            ],
            [
                'key' => 'photograph',
                'label' => 'Photograph'
            ]
        ]
    ]
];
