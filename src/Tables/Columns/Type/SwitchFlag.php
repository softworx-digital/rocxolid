<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

// rocXolid table column contracts
use Softworx\RocXolid\Tables\Columns\Contracts\Column;
// rocXolid table columns
use Softworx\RocXolid\Tables\Columns\AbstractColumn;

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
