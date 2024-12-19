<?php

return [
    'key' => 'widgets-text',
    'label' => 'Text',
    'type' => 'widget',
    'fields' => [
        [
            'name' => 'text',
            'label' => 'Text',
            'type' => 'text',
            "translatable" => true,
        ],
        [
            'name' => 'background_color',
            'label' => 'Background Color',
            'type' => 'select',
            'options' => [
                [
                    'value' => '#FFA666',
                    'label' => 'Orange'
                ],
                [
                    'value' => '#F5CB7A',
                    'label' => 'Yellow'
                ]
            ]
        ],
    ],
];
