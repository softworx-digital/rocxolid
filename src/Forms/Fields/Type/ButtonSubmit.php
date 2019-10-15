<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Helpers\View as ViewHelper;
use Softworx\RocXolid\Forms\Contracts\FormField;

class ButtonSubmit extends Button
{
    protected $default_options = [
        'type-template' => 'button-submit',
        // field wrapper
        'wrapper' => false,
        // field label
        'label' => false,
        // field HTML attributes
        'attributes' => [
            'class' => 'btn btn-primary'
        ],
    ];

    protected function setAjax($ajax): FormField
    {
        $this
            ->setComponentOptions('ajax', true)
            ->setComponentOptions('attributes', [
                'data-ajax-submit-form' => sprintf('#%s', $this->form->getOption('component.id')),
                //'data-ajax-submit-form' => ViewHelper::domIdHash($this->form, 'form'),
                'type' => 'button',
            ]);

        return $this;
    }
}
