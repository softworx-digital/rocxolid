<?php

namespace Softworx\RocXolid\Repositories\Columns\Type;

use Softworx\RocXolid\Repositories\Contracts\Column;
use Softworx\RocXolid\Repositories\Columns\AbstractColumn;

class DateTime extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'date-time',
        'format' => 'j.n.Y H:i:s',
        /*
        // field wrapper
        'wrapper' => false,
        // column HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
        */
    ];

    protected function setFormat($format): Column
    {
        return $this->setComponentOptions('format', $format);
    }
}
