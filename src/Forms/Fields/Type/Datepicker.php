<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Carbon\Carbon;
use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class Datepicker extends AbstractFormField
{
    protected $default_options = [
        'type-template' => 'datepicker',
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

    protected function adjustValueBeforeSet($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}
