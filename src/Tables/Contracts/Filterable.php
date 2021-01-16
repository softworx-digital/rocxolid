<?php

namespace Softworx\RocXolid\Tables\Contracts;

use Illuminate\Support\Collection;
// rocXolid table filter
use Softworx\RocXolid\Tables\Filters\Contracts\Filter;

/**
 * Enables to assign filters.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Filterable
{
    const FILTER_SESSION_PARAM = 'filter';

    /**
     * Set filter request values to session.
     *
     * @param array $values Values to set.
     * @return \Softworx\RocXolid\Tables\Contracts\Filterable
     */
    public function setFiltering(array $values): Filterable;

    /**
     * Clear table filtering.
     *
     * @return \Softworx\RocXolid\Tables\Contracts\Filterable
     */
    public function clearFiltering(): Filterable;

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
     * Retrieve single filter by its name.
     *
     * @param string $filter_name Filter name to retrieve filter.
     * @return \Softworx\RocXolid\Tables\Filters\Contracts\Filter
     */
    public function getFilter(string $filter_name): Filter;

    /**
     * Obtain values from session for given filter.
     *
     * @param \Softworx\RocXolid\Tables\Filters\Contracts\Filter $filter
     * @return mixed
     * @todo change approach to clarify return value; currently a Collection, scalar or null can be returned
     */
    public function getFilterValue(Filter $filter);

    /**
     * Get route to submit filter values.
     *
     * @return string
     */
    public function getFilteringRoute(): string;
}
