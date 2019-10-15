<?php

namespace Softworx\RocXolid\Traits;

use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Contracts\Repositoryable as RepositoryableContract;

/**
 * Enables object to have a repository assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Repositoryable
{
    /**
     * @var \Softworx\RocXolid\Repositories\Contracts\Repository Repository holder.
     */
    protected $repository;

    /**
     * {@inheritdoc}
     */
    public function setRepository(Repository $repository): RepositoryableContract
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        if (!$this->hasRepository()) {
            throw new \UnderflowException(sprintf('No repository set in [%s]', get_class($this)));
        }

        return $this->repository;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRepository(): bool
    {
        return isset($this->repository);
    }
}
