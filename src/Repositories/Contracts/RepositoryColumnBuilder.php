<?php

namespace Softworx\RocXolid\Repositories\Contracts;

interface RepositoryColumnBuilder
{
    public function addDefinitionColumns(Repository $repository, $definition): RepositoryColumnBuilder;

    public function addDefinitionButtons(Repository $repository, $definition): RepositoryColumnBuilder;

    public function makeColumn(Repository $repository, $type, $name, array $options = []): Column;
}
