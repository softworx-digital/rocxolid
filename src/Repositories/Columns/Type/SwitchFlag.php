<?php

namespace Softworx\RocXolid\Repositories\Columns\Type;

// rocXolid table column contracts
use Softworx\RocXolid\Repositories\Contracts\Column;
// rocXolid table columns
use Softworx\RocXolid\Repositories\Columns\AbstractColumn;

class SwitchFlag extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'flag-switch',
        'ajax' => true,
        'action' => 'switchEnability',
        /*
        // field wrapper
        'wrapper' => false,
        // column HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
        */
    ];

    protected function setAction(string $action): Column
    {
        return $this->setComponentOptions('action', $action);
    }

    protected function setAjax(bool $ajax): Column
    {
        return $this->setComponentOptions('ajax', $ajax);
    }
}
