<?php

namespace Softworx\RocXolid\Filters;

use Illuminate\Database\Eloquent;
use Softworx\RocXolid\Models\Contracts\Crudable;

class Contains
{
    public function apply($query, $queried_model, Crudable $model)
    {
        if (is_numeric($model->getQueryString())) {
            return $query->where(sprintf('%s.id', $queried_model->getTable()), 'like', sprintf('%%%s%%', $model->getQueryString()));
        } else {
            return $query->where(sprintf('%s.%s', $queried_model->getTable(), $queried_model->getSearchColumn()), 'like', sprintf('%%%s%%', $model->getQueryString()));
        }
    }
}
