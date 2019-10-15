<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class Input extends AbstractFormField
{
    protected $default_options = [
        'type-template' => 'text',
        // field wrapper
        'wrapper' => false,
        // component helper classes
        'helper-classes' => [
            'error-class' => 'has-error',
            'success-class' => 'has-success',
        ],
        // field label
        'label' => false,
        // field HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
    ];
    /*
    protected $default_options = [
        'type-template' => 'text',
        // field wrapper
        'wrapper' => [
            'attributes' => [
                // field wrapper HTML attributes
                'class' => 'col-md-6 col-sm-6 col-xs-12'
            ]
        ],
        // field label
        'label' => [
            // field label HTML attributes
            'attributes' => [
                'class' => 'control-label col-md-2 col-sm-2 col-xs-12',
            ],
        ],
        // field HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
    ];
    */
    /*
    protected $_fields = [
        'test' => [
            'type' => FormFieldType::TEXT,
            'options' => [
                'wrapper' => [
                    'class' => 'form-group'
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
                'help_block' => [
                    'text' => 'shkjfhsdkfjhdsfkj',
                    'tag' => 'p',
                    'attr' => [
                        'class' => 'help-block'
                    ]
                ],
                'default_value' => null, // Fallback value if none provided by value property or model
                'label' => 'test',  // Field name used
                'label_show' => true,
                'label_attr' => [
                    'class' => 'control-label col-md-3 col-sm-3 col-xs-12',
                    'for' => 'test'
                ],
                'errors' => [
                    'class' => 'text-danger'
                ],
                'rules' => [],           // Validation rules
                'error_messages' => [],   // Validation error messages
                'template' => 'rocXolid::form.field.text',
            ],
        ],
    ];
    */
}
