<?php

namespace Softworx\RocXolid\Repositories\Columns\Type;

use Softworx\RocXolid\Repositories\Columns\AbstractColumn;

class Rating extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'rating',
        /*
        // field wrapper
        'wrapper' => false,
        // column HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
        */
    ];

    public function setMax($max)
    {
        return $this->setComponentOptions('max', $max);
    }
}
