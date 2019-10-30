<?php

namespace Softworx\RocXolid\Repositories\Contracts;

use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Components\Contracts\Repositoryable as RepositoryableComponent;

interface Repositoryable
{
    const REPOSITORY_PARAM = 'general';

    public function createRepository(string $class, string $param = self::REPOSITORY_PARAM): Repository;

    public function setRepository(Repository $repository, string $param = self::REPOSITORY_PARAM): Repositoryable;

    public function getRepositories(): array;

    public function getRepository(string $param = self::REPOSITORY_PARAM): Repository;

    public function hasRepositoryAssigned(string $param = self::REPOSITORY_PARAM): bool;

    public function hasRepositoryClass(string $param = self::REPOSITORY_PARAM): bool;

    public function getRepositoryComponent(Repository $repository): RepositoryableComponent;
}
