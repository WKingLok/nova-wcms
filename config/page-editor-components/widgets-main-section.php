<?php

/**
 * type = text / select / switch / checkbox / textarea / tinymce / image / multiselect / image-collection / file
 */

return [
    'key' => 'widgets-main-section',
    'label' => 'Main Section',
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
            'name' => 'image',
            'label' => 'Image',
            'type' => 'image',
            "translatable" => false,
        ],
    ],
];
