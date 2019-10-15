<?php

namespace Softworx\RocXolid\Repositories\Columns\Type;

use Softworx\RocXolid\Repositories\Contracts\Column;
use Softworx\RocXolid\Repositories\Columns\AbstractColumn;

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
