<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Contracts\FormField;
use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class Select extends AbstractFormField
{
    protected $default_options = [
        'type-template' => 'select',
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
            'class' => 'form-control',
            //'data-live-search' => true,
        ],
    ];

    protected function setChoices($choices): FormField
    {
        return $this->setComponentOptions('choices', $choices);
    }
}
