<?php

namespace Softworx\RocXolid\Tables\Buttons\Type;

use Softworx\RocXolid\Tables\Buttons\AbstractButton;
use Softworx\RocXolid\Tables\Buttons\Contracts\Button;
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;

// @todo zatial extenduje AbstractButton - nejako rozdelit zrejme na buttony a columny (fieldy)
class ButtonAnchor extends AbstractButton
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

    protected function setUrl(string $url): Button
    {
        return $this
            ->setComponentOptions('url', $url)
            ->setAjax($this->getOption('component.ajax', false)); // reset ajax url
    }

    protected function setAction(string $action): Button
    {
        return $this->setComponentOptions('action', $action);
    }

    protected function setMethodAction(array $params): Button
    {
        return $this->setComponentOptions('method-action', $params);
    }

    protected function setRelationAction(array $params): Button
    {
        return $this->setComponentOptions('relation-action', $params);
    }

    protected function setRelatedAction(array $params): Button
    {
        return $this->setComponentOptions('related-action', $params);
    }

    protected function setForeignAction(array $params): Button
    {
        return $this->setComponentOptions('foreign-action', $params);
    }

    public function setRouteParams(array $route_params): Button
    {
        $this->setComponentOptions('route-params', $route_params);

        return $this;
    }

    public function setTel(string $model_attribute): Button
    {
        $this->setComponentOptions('tel', $model_attribute);

        return $this;
    }

    public function setMailto(string $model_attribute): Button
    {
        $this->setComponentOptions('mailto', $model_attribute);

        return $this;
    }

    protected function setAjax(bool $ajax): Button
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
