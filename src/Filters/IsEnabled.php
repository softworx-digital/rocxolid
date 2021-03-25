<?php

namespace Softworx\RocXolid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class IsEnabled
{
    public function apply(Builder $query, Model $queried_model, bool $is_enabled)
    {
        return $query->where(sprintf('%s.is_enabled', $queried_model->getTable(), $queried_model->getKeyName()), $is_enabled);
    }
}
