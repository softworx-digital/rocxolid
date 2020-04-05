<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

use Softworx\RocXolid\Tables\Columns\Contracts\Column;
use Softworx\RocXolid\Tables\Columns\AbstractColumn;

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
