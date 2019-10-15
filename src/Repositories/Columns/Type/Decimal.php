<?php

namespace Softworx\RocXolid\Repositories\Columns\Type;

// contracts
use Softworx\RocXolid\Repositories\Contracts\Column;
// column types
use Softworx\RocXolid\Repositories\Columns\AbstractColumn;

/**
 *
 */
class Decimal extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'decimal',
        /*
        // field wrapper
        'wrapper' => false,
        // column HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
        */
    ];

    protected function setUnit($unit): Column
    {
        return $this->setComponentOptions('unit', $unit);
    }
}
