<?php

namespace Softworx\RocXolid\Tables\Filters\Type;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Filterable;
use Softworx\RocXolid\Tables\Filters\Contracts\Filter;
// rocXolid table filters
use Softworx\RocXolid\Tables\Filters\AbstractFilter;

class CheckboxToggle extends AbstractFilter
{
    protected $default_options = [
        'type-template' => 'checkbox-toggle',
        // field wrapper
        'wrapper' => false,
        // reset button
        'reset-button' => false,
        // field HTML attributes
        // field HTML attributes
        'attributes' => [
            'data-toggle' => 'toggle',
            'data-size' => 'small',
            'data-width' => '60',
            // 'data-style' => 'round',
            'data-on' => '<i class=\'fa fa-check\'></i>',
            'data-off' => '<i class=\'fa fa-close\'></i>',
        ],
    ];

    public function apply(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where($this->getColumnName($query), true);
    }
}
