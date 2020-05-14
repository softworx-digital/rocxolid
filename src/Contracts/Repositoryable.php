<?php

namespace Softworx\RocXolid\Contracts;

use Softworx\RocXolid\Repositories\Contracts\Repository;

/**
 * Enables object to have a repository assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Repositoryable
{
    /**
     * Set the repository.
     *
     * @param \Softworx\RocXolid\Repositories\Contracts\Repository
     * @return \Softworx\RocXolid\Contracts\Repositoryable
     */
    public function setRepository(Repository $repository): Repositoryable;

    /**
     * Get the repository.
     *
     * @return \Softworx\RocXolid\Repositories\Contracts\Repository
     * @throws \UnderflowException If no repository is set.
     */
    public function getRepository(): Repository;

    /**
     * Check if the repository is assigned.
     *
     * @return bool
     */
    public function hasRepository(): bool;
}
