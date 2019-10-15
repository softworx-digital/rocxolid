<?php

namespace Softworx\RocXolid\Repositories\Contracts;

use Illuminate\Support\Collection;

interface Filterable
{
    public function addFilter(Filter $filter): Filterable;

    public function setFilters($filters): Filterable;

    public function getFilters(): Collection;
    /**
     * @param bool $use_filters
     * @return $this
     */
    //public function setUseFilters($use_filters = true): Filterable;
}
