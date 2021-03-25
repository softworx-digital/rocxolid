<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

use Illuminate\Support\Collection;
// contracts
use Softworx\RocXolid\Tables\Columns\Contracts\Column;
// column types
use Softworx\RocXolid\Tables\Columns\AbstractColumn;

/**
 *
 */
class Label extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'label',
        /*
        // field wrapper
        'wrapper' => false,
        // column HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
        */
    ];
}
