<?php

namespace Softworx\RocXolid\Tables\Contracts;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Tables\Contracts\Filter;

/**
 * Enables to assign filters.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo: revise & complete
 */
interface Filterable
{
    /**
     * Add filter to container.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Filter $filter
     * @return \Softworx\RocXolid\Tables\Contracts\Filterable
     */
    public function addFilter(Filter $filter): Filterable;

    /**
     * Replace the filters with new filters collection.
     *
     * @param \Illuminate\Support\Collection $filters
     * @return \Softworx\RocXolid\Tables\Contracts\Filterable
     */
    public function setFilters(Collection $filters): Filterable;

    /**
     * Get assigned filters.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFilters(): Collection;

    /**
     * Get single filter by its name.
     *
     * @param string $name
     * @return \Softworx\RocXolid\Tables\Contracts\Filter
     */
    public function getFilter(string $name): Filter;


    /**
     * @param bool $use_filters
     * @return $this
     */
    //public function setUseFilters($use_filters = true): Filterable;
}
