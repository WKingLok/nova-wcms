<?php

/**
 * type = text / select / switch / checkbox / textarea / tinymce / image / multiselect / image-collection / file
 */

return [
    'key' => 'widgets-service-section',
    'label' => 'Service Section',
    'type' => 'widget',
    'fields' => [
        [
            'name' => 'title',
            'label' => 'Title',
            'type' => 'text',
            "translatable" => true,
        ],
        [
            'name' => 'description',
            'label' => 'Description',
            'type' => 'tinymce',
            "translatable" => true,
        ],
        [
            'name' => 'services',
            'label' => 'Services',
            "repeatable" => true,
            "nested" => [
                [
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'text',
                    "translatable" => true,
                ],
                [
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'tinymce',
                    "translatable" => true,
                ],
                [
                    'name' => 'icon',
                    'label' => 'Icon',
                    'type' => 'image',
                    "translatable" => false,
                ],
            ]
        ]
    ],
];
