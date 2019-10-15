<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class Hidden extends AbstractFormField
{
    protected $default_options = [
        'type-template' => 'hidden',
        // field wrapper
        'wrapper' => false,
        // field label
        'label' => false,
    ];
}
