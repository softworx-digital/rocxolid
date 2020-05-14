<?php

namespace Softworx\RocXolid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Softworx\RocXolid\Models\Contracts\Crudable;

class Closurable
{
    public function apply(Builder $query, Model $quered_model, \Closure $closure)
    {
        return $closure($query, $quered_model);
    }
}
