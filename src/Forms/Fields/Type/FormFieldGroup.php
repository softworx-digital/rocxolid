<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class FormFieldGroup extends AbstractFormField
{
    const DEFAULT_NAME = '__default__';

    protected $default_options = [
        // field wrapper
        'wrapper' => [
            'class' => 'row',
        ],
        // field label
        'label' => false,
        // field HTML attributes
        'attributes' => [
            'class' => 'form-field-group'
        ],
    ];
}
