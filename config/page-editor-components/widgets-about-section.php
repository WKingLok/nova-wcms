<?php

/**
 * type = text / select / switch / checkbox / textarea / tinymce / image / multiselect / image-collection / file
 */

return [
    'key' => 'widgets-about-section',
    'label' => 'About Section',
    'type' => 'widget',
    'fields' => [
        [
            'name' => 'title',
            'label' => 'Title',
            'type' => 'text',
            "translatable" => true,
        ],
        [
            'name' => 'content',
            'label' => 'Content',
            'type' => 'tinymce',
            "translatable" => true,
        ],
        [
            'name' => 'readMoreLabel',
            'label' => 'Read More label',
            'type' => 'text',
            "translatable" => true,
        ],
    ],
];
