<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

use Illuminate\Support\Collection;
// contracts
use Softworx\RocXolid\Tables\Contracts\Column;
// column types
use Softworx\RocXolid\Tables\Columns\AbstractColumn;

/**
 *
 */
class Text extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'text',
        /*
        // field wrapper
        'wrapper' => false,
        // column HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
        */
    ];

    protected function setShorten($max): Column
    {
        return $this->setComponentOptions('shorten', $max);
    }

    protected function setTranslate(Collection $translation): Column
    {
        return $this->setComponentOptions('translate', $translation);
    }
}
