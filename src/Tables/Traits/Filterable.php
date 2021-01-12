<?php

namespace Softworx\RocXolid\Tables\Traits;

use Illuminate\Support\Collection;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Filters\Contracts\Filter;
use Softworx\RocXolid\Tables\Contracts\Filterable as FilterableContract;

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
     * @todo rename table property to 'filters_definition' or similar and this to 'filters'
     */
    private $filters_container;

    /**
     * {@inheritDoc}
     */
    public function setFiltering(array $values): FilterableContract
    {
        $this->resetPagination();

        $values = collect($values)->filter(function ($value, $name) {
            return $this->hasFilter($name)
                && $this->getFilter($name)->setValue($value);
        });
logger($values);
        $this->getRequest()->session()->put($this->getSessionKey(FilterableContract::FILTER_SESSION_PARAM), $values);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function clearFiltering(): FilterableContract
    {
        $this->getRequest()->session()->forget($this->getSessionKey(FilterableContract::FILTER_SESSION_PARAM));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setFilters(Collection $filters): FilterableContract
    {
        $this->filters_container = $filters;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters(): Collection
    {
        return collect($this->filters_container);
    }

    /**
     * {@inheritDoc}
     */
    public function getFilterValue(Filter $filter)
    {
        $session_values = $this->getRequest()->session()->get($this->getSessionKey(FilterableContract::FILTER_SESSION_PARAM));

        return $session_values ? $session_values->get($filter->getName()) : null;
    }

    /**
     * {@inheritDoc}
     */
    public function getFilteringRoute(): string
    {
        return $this->getController()->getRoute('tableFilter', [
            'param' => $this->getParam(),
        ]);
    }

    /**
     * Add filter to container.
     *
     * @param \Softworx\RocXolid\Tables\Filters\Contracts\Filter $filter Filter to add.
     * @return \Softworx\RocXolid\Tables\Contracts\Filterable
     */
    protected function addFilter(Filter $filter): FilterableContract
    {
        $this->getFilters()->put($filter->getName(), $filter);

        return $this;
    }

    /**
     * Retrieve single filter by its name.
     *
     * @param string $filter_name Filter name to retrieve filter for.
     * @return \Softworx\RocXolid\Tables\Filters\Contracts\Filter
     */
    protected function getFilter(string $filter_name): Filter
    {
        if ($this->getFilters()->has($filter_name)) {
            return $this->getFilters()->get($filter_name);
        }

        throw new \InvalidArgumentException(sprintf('Invalid filter (name) [%s] requested in [%s]', $filter_name, get_class($this)));
    }

    /**
     * Check if the filter is present.
     *
     * @param string $filter_name Filter name to check.
     * @return bool
     */
    protected function hasFilter(string $filter_name): bool
    {
        return $this->getFilters()->has($filter_name);
    }
}
