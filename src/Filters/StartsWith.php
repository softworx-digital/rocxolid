<?php

namespace Softworx\RocXolid\Filters;

use Illuminate\Database\Eloquent\Builder;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Searchable;

/**
 * Dynamic query "scope" to filter results with column(s) values starting with given string.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class StartsWith
{
    /**
     * Apply filter.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Softworx\RocXolid\Models\Contracts\Searchable $model
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $query, Searchable $model, string $search): Builder
    {
        return $query->where(function (Builder $query) use ($model, $search) {
            $model->getSearchColumns()->each(function (string $column) use ($query, $model, $search) {
                $query->orWhere($model->qualifyColumn($column), 'like', sprintf('%s%%', $search));
            });
        });
    }

    /**
     * Apply filter with disjunction.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Softworx\RocXolid\Models\Contracts\Searchable $model
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     * @todo hotfixed needed feature by adding following method
     */
    public function applyDisjunct(Builder $query, Searchable $model, string $search): Builder
    {
        return $query->orWhere(function (Builder $query) use ($model, $search) {
            $model->getSearchColumns()->each(function (string $column) use ($query, $model, $search) {
                $query->orWhere($model->qualifyColumn($column), 'like', sprintf('%s%%', $search));
            });
        });
    }
}
