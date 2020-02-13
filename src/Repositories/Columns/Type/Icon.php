<?php

namespace Softworx\RocXolid\Repositories\Columns\Type;

use Softworx\RocXolid\Repositories\Columns\AbstractColumn;

class Icon extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'icon',
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
