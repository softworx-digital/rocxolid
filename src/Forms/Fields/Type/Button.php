<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class Button extends AbstractFormField
{
    protected $default_options = [
        'type-template' => 'button',
        // field wrapper
        'wrapper' => false,
        // field label
        'label' => false,
        // field HTML attributes
        'attributes' => [
            'class' => 'btn btn-primary'
        ],
    ];
}
