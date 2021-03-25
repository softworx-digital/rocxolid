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

    public function setPolicyAbility(string $policy_ability): Button
    {
        $this->setComponentOptions('policy-ability', $policy_ability);

        return $this;
    }

    protected function setUrl($url): FormField
    {
        return $this
            ->setComponentOptions('url', $url)
            ->setAjax($this->getOption('component.ajax', false)); // reset ajax url
    }

    protected function setAction(string $action): Button
    {
        return $this->setUrl($this->getForm()->getController()->getRoute($action, $this->getForm()->getModel(), $this->getOption('route-params', [])));
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
        if ($ajax) {
            $this
                ->setComponentOptions('ajax', $ajax)
                ->setComponentOptions('attributes', [
                    'data-ajax-url' => $this->getOption('component.url'),
                    'type' => 'button',
                ]);
        } else {
            $this
                ->setComponentOptions('ajax', $ajax)
                ->removeOption('component.attributes.data-ajax-url');
        }

        return $this;
    }
}
