<?php

namespace Softworx\RocXolid\Tables\Filters\Type;

use Illuminate\Database\Eloquent\Builder;
// rocXolid query filters
use Softworx\RocXolid\Filters\Contains;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Filters\Contracts\Filter;
// rocXolid table filters
use Softworx\RocXolid\Tables\Filters\AbstractFilter;

class ModelAttribute extends AbstractFilter
{
    protected $default_options = [
        'type-template' => 'text',
        // search columns
        'columns' => null,
        // reset button
        'reset-button' => true,
        // field wrapper
        'wrapper' => false,
        // field HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
    ];

    protected $search_columns;

    public function apply(Builder $query): Builder
    {
        $model = $query->getModel();

        !$this->search_columns ?: $model->setSearchColumns($this->search_columns);

        return app(Contains::class)->apply($query, $model, $this->getValue());
    }

    public function setColumns(?array $columns): Filter
    {
        $this->search_columns = $columns;

        return $this;
    }
}
