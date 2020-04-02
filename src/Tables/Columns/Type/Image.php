<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

use Softworx\RocXolid\Tables\Contracts\Column;
use Softworx\RocXolid\Tables\Columns\AbstractColumn;

class Image extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'image',
        /*
        // field wrapper
        'wrapper' => false,
        // column HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
        */
    ];

    protected function setPath($path): Column
    {
        return $this->setComponentOptions('path', $path);
    }
}
