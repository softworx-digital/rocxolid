<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class ValueSlider extends AbstractFormField
{
    protected $default_options = [
        'type-template' => 'value-slider',
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
            'data-provide' => 'slider',
            'class' => 'form-control'
        ],
    ];
}
