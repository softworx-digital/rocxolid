<?php

namespace Softworx\RocXolid\Repositories\Events;

use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Repositories\Contracts\Filter;

class AfterFilterCreation
{
    /**
     * The repository instance.
     *
     * @var Repository
     */
    protected $repository;

    /**
     * The filter instance.
     *
     * @var Filter
     */
    protected $filter;

    /**
     * Create a new after filter creation instance.
     *
     * @param Repository $repository
     * @param Filter $column
     * @return void
     */
    public function __construct(Repository $repository, Filter $filter)
    {
        $this->repository = $repository;
        $this->filter = $filter;
    }

    /**
     * Return the event's repository.
     *
     * @return Repository
     */
    public function getRepository(): Repository
    {
        return $this->repository;
    }

    /**
     * Return the event's filter.
     *
     * @return Filter
     */
    public function getFilter(): Filter
    {
        return $this->filter;
    }
}
