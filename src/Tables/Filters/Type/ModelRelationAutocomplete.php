<?php

namespace Softworx\RocXolid\Tables\Filters\Type;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
// rocXolid query filters
use Softworx\RocXolid\Filters\StartsWith;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Filters\Contracts\Filter;
// rocXolid table filters
use Softworx\RocXolid\Tables\Filters\AbstractFilter;

class ModelRelationAutocomplete extends AbstractFilter
{
    const LIMIT = 10;

    protected $default_options = [
        'type-template' => 'model-relation',
        // autocompletion settings
        'autocomplete' => [
            'filters' => [
                StartsWith::class
            ],
        ],
        // relation used for this field
        'relation' => null,
        // field wrapper
        'wrapper' => false,
        // reset button
        'reset-button' => true,
        // field HTML attributes
        'attributes' => [
            'class' => 'form-control autocomplete',
            'data-live-search' => 'true',
        ],
    ];

    protected $model_relation;

    protected $queried_model;

    protected $model_filters;

    protected $autocomplete_filters;

    protected $autocomplete_columns;

    public function apply(Builder $query): Builder
    {
        return $query->where($this->getColumnName($query), $this->getValue());
        /*
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
        */
    }

    public function autocomplete(string $search): Collection
    {
        !$this->autocomplete_columns ?: $this->queried_model->setSearchColumns($this->autocomplete_columns);

        $query = $this->queried_model->query();
        $query->join(
            $this->model_relation->getParent()->getTable(),
            $this->model_relation->getQualifiedOwnerKeyName(), '=', $this->model_relation->getQualifiedForeignKeyName()
        );

        collect($this->autocomplete_filters)->each(function (string $type) use ($query, $search) {
            app($type)->apply($query, $this->queried_model, $search);
        });

        collect($this->model_filters)->each(function ($definition) use ($query) {
            extract($definition);
            app($type)->apply($query, $this->queried_model, $data);
        });

        return $query
            ->select($this->queried_model->qualifyColumn('*'))
            ->distinct()
            ->take(static::LIMIT)
            ->get();
    }

    public function setRelation(string $relation): Filter
    {
        $this->model_relation = $this->getTable()->getController()->getRepository()->getModel()->{$relation}();
        $this->queried_model = $this->model_relation->getRelated();

        return $this;
    }

    public function setModelFilters(array $model_filters): Filter
    {
        $this->model_filters = $model_filters;

        return $this;
    }

    public function setAutocomplete(array $settings): Filter
    {
        return $this
            ->setAutocompleteColumns($settings['columns'] ?? null)
            ->setAutocompleteFilters($settings['filters'] ?? null)
            ->setComponentOptions('attributes', [ 'data-abs-ajax-url' => $settings['url'] ?? $this->getAutocompleteUrl() ]);
    }

    public function getCollection(): Collection
    {
        if ($this->hasValue() && ($model = $this->model_relation->getRelated()->find($this->getValue()))) {
            return collect([
                $model->getKey() => $model->getTitle()
            ]);
        }

        return collect();
    }

    private function setAutocompleteColumns(?array $columns): Filter
    {
        $this->autocomplete_columns = $columns;

        return $this;
    }

    private function setAutocompleteFilters(?array $filters): Filter
    {
        $this->autocomplete_filters = $filters;

        return $this;
    }

    private function getAutocompleteUrl(): string
    {
        return $this->getTable()->getController()->getRoute('tableFilterAutocomplete', [
            'param' => $this->getTable()->getParam(),
            'filter' => $this->getName(),
        ]);
    }
}
