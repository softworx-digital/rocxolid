<?php

namespace Softworx\RocXolid\Repositories\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\Filter;
use Softworx\RocXolid\Repositories\Contracts\Filterable as FilterableContract;

/**
 * Trait to enable model data filtering.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Filterable
{
    /**
     * Filters container.
     *
     * @var \Illuminate\Support\Collection
     */
    private $filters;

    /**
     * {@inheritDoc}
     */
    public function setFilters(Collection $filters): FilterableContract
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters(): Collection
    {
        return collect($this->filters);
    }

    /**
     * Apply filter conditions to query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Softworx\RocXolid\Repositories\Contracts\Filterable
     */
    protected function applyFilters(EloquentBuilder &$query): FilterableContract
    {
        $this->getFilters()->filter(function($filter) {
            return $filter->isAppliable();
        })->each(function($filter) use ($query) {
            $query = $filter->apply($query);
        });

        return $this;
    }
}
