<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

use Softworx\RocXolid\Tables\Columns\AbstractColumn;

class Flag extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'flag',
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
