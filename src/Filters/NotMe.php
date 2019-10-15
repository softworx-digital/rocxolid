<?php

namespace Softworx\RocXolid\Filters;

use Illuminate\Database\Eloquent;
use Softworx\RocXolid\Models\Contracts\Crudable;

class NotMe
{
    public function apply($query, Crudable $model)
    {
        return $query->where(sprintf('%s.id', $model->getTable()), '!=', $model->id);
    }
}
