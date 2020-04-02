<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

// contracts
use Softworx\RocXolid\Tables\Contracts\Column;
// column types
use Softworx\RocXolid\Tables\Columns\AbstractColumn;

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
