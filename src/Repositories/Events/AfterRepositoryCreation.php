<?php

namespace Softworx\RocXolid\Repositories\Events;

use Softworx\RocXolid\Repositories\Contracts\Repository;

class AfterRepositoryCreation
{
    /**
     * The repository instance.
     *
     * @var Repository
     */
    protected $repository;

    /**
     * Create a new after repository creation instance.
     *
     * @param Repository $repository
     * @return void
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Return the event's repository.
     *
     * @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}
