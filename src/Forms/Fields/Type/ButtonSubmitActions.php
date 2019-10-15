<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Contracts\FormField;

class ButtonSubmitActions extends Button
{
    protected $default_options = [
        'type-template' => 'button-submit-actions',
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
                'type' => 'button',
            ]);

        return $this;
    }

    protected function setActions($actions): FormField
    {
        $this
            ->setComponentOptions('actions', $actions);

        return $this;
    }
}
