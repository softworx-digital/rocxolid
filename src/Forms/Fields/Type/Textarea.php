<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class Textarea extends AbstractFormField
{
    protected $default_options = [
        'type-template' => 'textarea',
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
}
