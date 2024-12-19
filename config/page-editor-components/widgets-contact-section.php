<?php

/**
 * type = text / select / switch / checkbox / textarea / tinymce / image / multiselect / image-collection / file
 */

return [
    'key' => 'widgets-contact-section',
    'label' => 'Contact Section',
    'type' => 'widget',
    'fields' => [
        [
            'name' => 'title',
            'label' => 'Title',
            'type' => 'text',
            "translatable" => true,
        ],
        [
            'name' => 'emailFieldLabel',
            'label' => 'Email field label',
            'type' => 'text',
            "translatable" => true,
        ],
        [
            'name' => 'messageFieldLabel',
            'label' => 'Message field label',
            'type' => 'text',
            "translatable" => true,
        ],
        [
            'name' => 'sendLabel',
            'label' => 'Send label',
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
