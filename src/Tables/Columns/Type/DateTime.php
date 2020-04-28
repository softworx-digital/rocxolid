<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

use Softworx\RocXolid\Tables\Columns\Type\Date;

class DateTime extends Date
{
    protected $default_options = [
        'type-template' => 'date-time',
        'format' => null,
        'isoFormat' => 'LLLL',
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
