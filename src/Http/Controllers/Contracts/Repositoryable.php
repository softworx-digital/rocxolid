<?php

namespace Softworx\RocXolid\Http\Controllers\Contracts;

use Softworx\RocXolid\Repositories\Contracts\Repository;

/**
 * Interface to connect the controller with a repository.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Repositoryable
{
    const REPOSITORY_PARAM = 'general';

    public function createRepository(string $class, string $param = self::REPOSITORY_PARAM): Repository;

    public function setRepository(Repository $repository, string $param = self::REPOSITORY_PARAM): Repositoryable;

    public function getRepositories(): array;

    public function getRepository(string $param = self::REPOSITORY_PARAM): Repository;

    public function hasRepositoryAssigned(string $param = self::REPOSITORY_PARAM): bool;

    public function hasRepositoryClass(string $param = self::REPOSITORY_PARAM): bool;
}
