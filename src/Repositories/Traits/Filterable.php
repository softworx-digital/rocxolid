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
    private $_filters = null;

    protected function applyFilters(EloquentBuilder &$query): FilterableContract
    {
        foreach ($this->getFilters() as $filter) {
            if ($this->useFilter($filter)) {
                $this->query = $filter->apply($this);
            }
        }

        return $this;
    }

    protected function useFilter(Filter $filter)
    {
        return !empty($filter->getValue());
    }

    protected function setFilterValues(): FilterableContract
    {
        $input = new Collection($this->getFilterInput());

        if ($input->isNotEmpty()) {
            $input->each(function ($value, $name) {
                $this
                    ->getFilter($name)
                        ->setValue($value);
            });
        }

        return $this;
    }

    protected function getFilterInput(): array
    {
        $input = $this->getRequest()->has(Filter::DATA_PARAM)
               ? $this->getRequest()->input(Filter::DATA_PARAM)
               : $this->getRequest()->session()->get($this->getSessionParam('filter'), []);

        // applying new filter
        if ($this->getRequest()->has(Filter::DATA_PARAM)) {

            $this->getRequest()->session()->forget(md5(get_class($this)) . '_page'); // reset paging
            $this->getRequest()->session()->put($this->getSessionParam('filter'), $this->getRequest()->input(Filter::DATA_PARAM));
        }

        return $input;
    }

    public function addFilter(Filter $filter): FilterableContract
    {
        $this->getFilters()->put($filter->getName(), $filter);

        return $this;
    }

    public function hasFilter($filter): bool
    {
        return $this->getFilters()->has($filter);
    }

    public function getFilter($filter): Filter
    {
        if ($this->getFilters()->has($filter)) {
            return $this->getFilters()->get($filter);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid filter (name) [%s] requested in [%s]', $filter, get_class($this)));
        }
    }

    public function setFilterGroups($filter_groups): FilterableContract
    {
        $this->_filter_groups = new Collection($filter_groups);

        return $this;
    }

    public function setFilters($filters): FilterableContract
    {
        $this->_filters = new Collection($filters);

        return $this;
    }

    public function getFilters(): Collection
    {
        if (is_null($this->_filters)) {
            $this->_filters = new Collection();
        }

        return $this->_filters;
    }

    public function reorderFilters($order_definition): FilterableContract
    {
        if (is_null($order_definition)) {
            return $this;
        }

        if (!is_array($order_definition)) {
            throw new \InvalidArgumentException(sprintf('Fields order definition has to be an array, [%s] given', get_type($order_definition)));
        }

        $filters = $this->getFilters()->sortBy(function ($filter, $name) use ($order_definition) {
            return in_array($name, $order_definition) ? array_search($name, $order_definition) : PHP_INT_MAX;
        });

        return $this->setFilters($filters);
    }

    public function getFiltersValues(): Collection
    {
        $filters_values = new Collection();

        foreach ($this->getFilters() as $filter) {
            if (empty($filter->getValue()) && $filter->getValue() !== '0') {
                $filters_values->put($filter->getName(), null);
            } else {
                $filters_values->put($filter->getName(), $filter->getValue());
            }
        }

        return $filters_values;
    }

    public function clearFiltersValues(): FilterableContract
    {
        foreach ($this->getFilters() as $filter) {
            $filter->setValue(null);
        }

        return $this;
    }
}
