<?php

namespace Softworx\RocXolid\Repositories\Events;

use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Repositories\Contracts\Column;

class AfterColumnCreation
{
    /**
     * The repository instance.
     *
     * @var Repository
     */
    protected $repository;

    /**
     * The column instance.
     *
     * @var Column
     */
    protected $column;

    /**
     * Create a new after column creation instance.
     *
     * @param Repository $repository
     * @param Column $column
     * @return void
     */
    public function __construct(Repository $repository, Column $column)
    {
        $this->repository = $repository;
        $this->column = $column;
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
     * Return the event's column.
     *
     * @return Column
     */
    public function getColumn(): Column
    {
        return $this->column;
    }
}
