<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Contracts\FormField;

class ButtonAnchor extends Button
{
    protected $default_options = [
        'type-template' => 'button-anchor',
        // url to bind
        'url' => '#',
        // field wrapper
        'wrapper' => false,
        // field label
        'label' => false,
        // field HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
    ];

    protected function setUrl($url): FormField
    {
        return $this->setComponentOptions('url', $url);
    }

    protected function setRoute($route_name): FormField
    {
        return $this->setUrl($this->makeRoute($route_name));
    }

    protected function setRouteForm($route_name): FormField
    {
        return $this->setUrl($this->form->makeRoute($route_name));
    }

    protected function setAjax($ajax): FormField
    {
        $this
            ->setComponentOptions('ajax', true)
            ->setComponentOptions('attributes', [
                'data-ajax-url' => $this->getOption('component.url'),
                'type' => 'button',
            ]);

        return $this;
    }
}
