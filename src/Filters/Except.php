<?php

namespace Softworx\RocXolid\Filters;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Except
{
    public function apply(Builder $query, Model $queried_model, Collection $models)
    {
        return $query->whereNotIn(sprintf('%s.%s', $queried_model->getTable(), $queried_model->getKeyName()), $models->pluck('id'));
    }
}
