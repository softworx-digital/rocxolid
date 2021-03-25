<?php

namespace Softworx\RocXolid\Tables\Filters\Type;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Filterable;
use Softworx\RocXolid\Tables\Filters\Contracts\Filter;
// rocXolid table filters
use Softworx\RocXolid\Tables\Filters\AbstractFilter;

class DateTime extends AbstractFilter
{
    protected $default_options = [
        'type-template' => 'date-time',
        'format' => 'j.n.Y H:i:s',
        // reset button
        'reset-button' => true,
        /*
        // field wrapper
        'wrapper' => false,
        // column HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
        */
    ];

    protected $is_range = false;

    public function apply(EloquentBuilder $query): EloquentBuilder
    {
        if ($this->is_range) {
            $query
                ->whereDate($this->getColumnName($query), '>=', Carbon::parse($this->getRangeFromValue())->format('Y-m-d'))
                ->whereDate($this->getColumnName($query), '<=', Carbon::parse($this->getRangeToValue())->format('Y-m-d'));
        } else {
            $query->whereDate($this->getColumnName($query), '=', Carbon::parse($this->getValue())->format('Y-m-d'));
        }

        return $query;
    }

    public function isRange()
    {
        return $this->is_range;
    }

    public function getRangeFromValue()
    {
        $from = null;
        $split = preg_split('/( - )/', $this->value, 0, PREG_SPLIT_NO_EMPTY);

        if (!empty($split)) {
            list($from, $to) = $split;
        }

        return $from;
    }

    public function getRangeToValue()
    {
        $to = null;
        $split = preg_split('/( - )/', $this->value, 0, PREG_SPLIT_NO_EMPTY);

        if (!empty($split)) {
            list($from, $to) = $split;
        }

        return $to;
    }

    protected function setFormat($format): Filter
    {
        return $this->setComponentOptions('format', $format);
    }

    public function setRange(bool $is_range): Filter
    {
        $this->is_range = $is_range;

        return $this;
    }
}
