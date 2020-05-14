<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

use Carbon\Carbon;
use Softworx\RocXolid\Tables\Columns\Contracts\Column;
use Softworx\RocXolid\Tables\Columns\AbstractColumn;

class Date extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'date-time',
        'format' => null,
        'isoFormat' => 'l',
        /*
        // field wrapper
        'wrapper' => false,
        // column HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
        */
    ];

    public function setFormat($format): Column
    {
        $this->getOptions()->put('format', $format);

        return $this;
    }

    public function setIsoFormat($format): Column
    {
        $this->getOptions()->put('isoFormat', $format);

        return $this;
    }

    public function getModelAttributeViewValue($value)
    {
        if ($this->hasNotNullOption('format')) {
            return Carbon::make($value)->format($this->getOption('format'));
        } elseif ($this->hasNotNullOption('isoFormat')) {
            return Carbon::make($value)->locale(app()->getLocale())->isoFormat($this->getOption('isoFormat'));
        }

        return $value;
    }
}
