<?php

namespace Softworx\RocXolid\Repositories\Columns\Type;

use Softworx\RocXolid\Repositories\Columns\AbstractColumn;
use Softworx\RocXolid\Repositories\Contracts\Column;
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

    public function setPolicyAbility($policy_ability): Column
    {
        $this->setComponentOptions('policy-ability', $policy_ability);

        return $this;
    }

    public function setPolicyAbilityParams($policy_ability_params): Column
    {
        $this->setComponentOptions('policy-ability-params', $policy_ability_params);

        return $this;
    }

    public function setPolicyAbilityGroup($permissions_policy_ability_group): Column
    {
        $this->setComponentOptions('permissions_policy_ability_group', $permissions_policy_ability_group);

        return $this;
    }

    public function setTel($model_attribute): Column
    {
        $this->setComponentOptions('tel', $model_attribute);

        return $this;
    }

    public function setMailto($model_attribute): Column
    {
        $this->setComponentOptions('mailto', $model_attribute);

        return $this;
    }

    protected function setUrl($url): Column
    {
        return $this
            ->setComponentOptions('url', $url)
            ->setAjax($this->getOption('component.ajax', false)); // reset ajax url
    }

    protected function setRoute($route_name): Column
    {
        return $this->setUrl($this->makeRoute($route_name));
    }

    protected function setAjax($ajax): Column
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
