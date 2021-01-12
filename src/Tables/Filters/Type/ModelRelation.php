<?php

namespace Softworx\RocXolid\Tables\Filters\Type;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Filterable;
use Softworx\RocXolid\Tables\Filters\Contracts\Filter;
// rocXolid table filters
use Softworx\RocXolid\Tables\Filters\AbstractFilter;

class ModelRelation extends AbstractFilter
{
    protected $default_options = [
        'type-template' => 'model-relation',
        // field wrapper
        'wrapper' => false,
        // field label
        // 'label' => false,
        // field HTML attributes
        'attributes' => [
            'class' => 'form-control',
            'data-live-search' => 'true',
        ],
    ];

    protected $join = [];

    protected $join_table = false;

    protected $collection_model = null;

    public function apply(EloquentBuilder $query): EloquentBuilder
    {
        if ($this->join) {
            $table_column = sprintf('%s.id', $query->getModel()->getTable());
            $join_table_own_column = sprintf('%s.%s', $this->join['table'], $this->join['own_column']);
            $join_table_column = sprintf('%s.%s', $this->join['table'], $this->join['join_column']);

            return $query
                ->join($this->join['table'], $table_column, '=', $join_table_own_column)
                ->where($join_table_column, $this->getValue());
        } else {
            return $query->where($this->getColumnName($query), $this->getValue());
        }
    }

    public function setCollection($option): Filter
    {
        if ($option instanceof Collection) {
            $this->collection = $option;
        } else {
            $model = ($option['model'] instanceof Model) ? $option['model'] : new $option['model'];
            $query = $model::query();

            if (isset($option['filters'])) {
                foreach ($option['filters'] as $filter) {
                    $query = (new $filter['class']())->apply($query, $filter['data']);
                }
            }

            $this->collection_model = $model;
            $this->collection = $query->pluck(sprintf('%s.%s', $model->getTable(), $option['column']), sprintf('%s.id', $model->getTable()));
        }

        return $this;
    }

    public function getCollection(): Collection
    {
        $collection = $this->collection;
        // $collection->prepend($this->getOption('component.label.title'), '');

        return $collection;
    }

    public function setJoin($option): Filter
    {
        $this->join = $option;

        return $this;
    }
}
