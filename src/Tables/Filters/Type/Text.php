<?php

namespace Softworx\RocXolid\Tables\Filters\Type;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Filterable;
// rocXolid table filters
use Softworx\RocXolid\Tables\Filters\AbstractFilter;

class Text extends AbstractFilter
{
    protected $default_options = [
        'type-template' => 'text',
        // field wrapper
        'wrapper' => false,
        // reset button
        'reset-button' => true,
        // field HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
    ];

    public function apply(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where($this->getColumnName($query), 'like', sprintf('%%%s%%', $this->getValue()));
    }
}
