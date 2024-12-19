<?php

/**
 * type = text / select / switch / checkbox / textarea / tinymce / image / multiselect / image-collection / file
 */

return [
    'key' => 'widgets-portfolio-section',
    'label' => 'Portfolio Section',
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
            'type' => 'text',
            "translatable" => true,
        ],
        [
            'name' => 'showMoreLabel',
            'label' => 'Show More Label',
            'type' => 'text',
            "translatable" => true,
        ],
    ],
    'callback' => [
        [
            'name' => 'photographs',
            'class' => 'App\Http\Controllers\PhotographController',
            'function' => 'photographs',
            'args' => []
        ]
    ]
];
