<?php

namespace Softworx\RocXolid\Repositories\Contracts;

use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Components\Contracts\Repositoryable as RepositoryableComponent;

interface Repositoryable
{
    const REPOSITORY_PARAM = 'general';

    public function createRepository($class): Repository;

    public function setRepository(Repository $repository, $param = self::REPOSITORY_PARAM): Repositoryable;

    public function getRepositories();

    public function getRepository($param = self::REPOSITORY_PARAM): Repository;

    public function hasRepositoryAssigned($param = self::REPOSITORY_PARAM);

    public function hasRepositoryClass($param = self::REPOSITORY_PARAM);

    public function getRepositoryComponent(Repository $repository): RepositoryableComponent;
}
