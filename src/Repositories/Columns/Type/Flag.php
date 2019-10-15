<?php

namespace Softworx\RocXolid\Repositories\Columns\Type;

use Softworx\RocXolid\Repositories\Columns\AbstractColumn;

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
