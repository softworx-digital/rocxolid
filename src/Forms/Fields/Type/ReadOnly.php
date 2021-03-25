<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class ReadOnly extends AbstractFormField
{
    protected $is_hidden = true;

    protected $default_options = [
        'type-template' => 'read-only',
        // field wrapper
        'wrapper' => false,
        // field label
        'label' => false,
    ];
}
