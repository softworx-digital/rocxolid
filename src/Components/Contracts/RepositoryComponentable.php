<?php

namespace Softworx\RocXolid\Components\Contracts;

use Softworx\RocXolid\Components\Contracts\Repositoryable;

interface RepositoryComponentable
{
    public function setRepositoryComponent(Repositoryable $component): RepositoryComponentable;

    public function getRepositoryComponent(): Repositoryable;
}
