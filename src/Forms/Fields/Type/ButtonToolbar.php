<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class ButtonToolbar extends AbstractFormField
{
    const DEFAULT_NAME = '__default__';

    protected $default_options = [
        // field wrapper
        'wrapper' => false,
        // field label
        'label' => false,
        // field HTML attributes
        'attributes' => [
            'class' => 'btn-toolbar'
        ],
    ];
}
