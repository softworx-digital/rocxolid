<?php

namespace Softworx\RocXolid\Components\Contracts;

use Softworx\RocXolid\Repositories\Contracts\Filter;

interface TableFilterable
{
    public function setTableFilter(Filter $table_filter): TableFilterable;

    public function getTableFilter();
}
