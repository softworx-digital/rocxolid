<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class Radio extends AbstractFormField
{
    protected $default_options = [
        'type-template' => 'radio',
        // field wrapper
        'wrapper' => false,
        // component helper classes
        'helper-classes' => [
            'error-class' => 'has-error',
            'success-class' => 'has-success',
        ],
        // field label
        'label' => [
            'after' => true,
            'attributes' => [
                'class' => 'label-fit-height margin-left-5 margin-right-5'
            ]
        ],
        // field HTML attributes
        'attributes' => [
            //'class' => 'form-control'
        ],
    ];
}
