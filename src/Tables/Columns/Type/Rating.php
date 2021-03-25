<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

use Softworx\RocXolid\Tables\Columns\AbstractColumn;

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
