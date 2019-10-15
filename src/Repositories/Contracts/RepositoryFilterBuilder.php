<?php

namespace Softworx\RocXolid\Repositories\Contracts;

interface RepositoryFilterBuilder
{
    public function addDefinitionFilters(Repository $repository, $definition): RepositoryFilterBuilder;

    public function makeFilter(Repository $repository, $type, $name, array $options = []): Filter;
}
