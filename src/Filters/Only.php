<?php

namespace Softworx\RocXolid\Filters;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Only
{
    public function apply(Builder $query, Model $queried_model, Collection $models)
    {
        return $query->whereIn($queried_model->getQualifiedKeyName(), $models->pluck('id'));
    }
}
