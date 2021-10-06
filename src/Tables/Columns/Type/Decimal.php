<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

// contracts
use Softworx\RocXolid\Tables\Columns\Contracts\Column;
// column types
use Softworx\RocXolid\Tables\Columns\AbstractColumn;

/**
 *
 */
class Decimal extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'decimal',
        'wrapper' => [
            'attributes' => [
                'class' => 'text-center',
            ],
        ],
    ];

    protected function setSuffix($suffix): Column
    {
        return $this->setComponentOptions('suffix', $suffix);
    }
}
