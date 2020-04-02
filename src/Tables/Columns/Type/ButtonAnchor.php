<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

use Softworx\RocXolid\Tables\Columns\AbstractColumn;
use Softworx\RocXolid\Tables\Contracts\Column;
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;

// @todo - zatial extenduje AbstractColumn - nejako rozdelit zrejme na buttony a columny (fieldy)
class ButtonAnchor extends AbstractColumn
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

    public function setPolicyAbility(string $policy_ability): Column
    {
        $this->setComponentOptions('policy-ability', $policy_ability);

        return $this;
    }

    protected function setUrl(string $url): Column
    {
        return $this
            ->setComponentOptions('url', $url)
            ->setAjax($this->getOption('component.ajax', false)); // reset ajax url
    }

    protected function setAction(string $action): Column
    {
        return $this->setComponentOptions('action', $action);
    }

    public function setRouteParams(array $route_params): Column
    {
        $this->setComponentOptions('route-params', $route_params);

        return $this;
    }

    public function setTel(string $model_attribute): Column
    {
        $this->setComponentOptions('tel', $model_attribute);

        return $this;
    }

    public function setMailto(string $model_attribute): Column
    {
        $this->setComponentOptions('mailto', $model_attribute);

        return $this;
    }

    protected function setAjax(bool $ajax): Column
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
