<?php

/**
 * type = text / select / switch / checkbox / textarea / tinymce / image / multiselect / image-collection / file
 */

return [
    'key' => 'carousel',
    'label' => 'Carousel',
    'type' => 'widget',
    'fields' => [
        [
            'name' => 'title',
            'label' => 'Title',
            'type' => 'text',
            'default' => "Test Default Title",
            'validate' => 'required',
        ],
        [
            'name' => 'image',
            'label' => 'Background Image',
            'type' => 'image'
        ],
        [
            'name' => 'description',
            'label' => 'Description',
            'type' => 'tinymce',
            "translatable" => true,
            'validate' => 'required',
        ],
        [
            'name' => 'link_section',
            'label' => 'Link Section',
            'type' => 'text',
            "repeatable" => true,
            'validate' => 'required',
            "nested" => [
                [
                    'name' => 'links',
                    'label' => 'Links',
                    "type" => "text",
                    "repeatable" => true,
                    "nested" => [
                        [
                            'name' => 'links2',
                            'label' => 'Links 2',
                            "type" => "text",
                            "repeatable" => true,
                            "nested" => [
                                [
                                    'name' => 'link',
                                    'label' => 'Link',
                                    "type" => "text",
                                    "translatable" => true,
                                    'validate' => 'required',
                                ],
                            ]
                        ],
                    ]
                ],
                [
                    'name' => 'texts',
                    'label' => 'Texts',
                    "type" => "text",
                    "repeatable" => true,
                    'validate' => 'required',
                    "nested" => [
                        [
                            'name' => 'text',
                            'label' => 'Text',
                            "type" => "text",
                            "translatable" => true,
                            'validate' => 'required',
                        ],
                    ]
                ],
                [
                    'name' => 'label',
                    'label' => 'Label',
                    "type" => "text",
                    "translatable" => true,
                    'validate' => 'required',
                ],
            ]
        ]
    ],
    'callback' => [
        [
            'name' => 'exampleValue',
            'function' => 'example',
            'args' => [
                'title'
            ]
        ]
    ]
];
