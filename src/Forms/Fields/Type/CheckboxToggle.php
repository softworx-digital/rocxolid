<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class CheckboxToggle extends AbstractFormField
{
    protected $default_options = [
        'type-template' => 'checkbox-toggle',
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
                'class' => 'label-fit-height margin-left-10 margin-right-5',
            ],
        ],
        // field HTML attributes
        'attributes' => [
            'data-toggle' => 'toggle',
            'data-size' => 'small',
            'data-width' => '60',
            // 'data-style' => 'round',
            'data-on' => '<i class=\'fa fa-check\'></i>',
            'data-off' => '<i class=\'fa fa-close\'></i>',
        ],
        'validation' => [
            'rules' => [
                'boolean', // @todo: verify it's actually working
            ],
        ],
    ];
}
