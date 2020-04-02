<?php

namespace Softworx\RocXolid\Tables\Builders\Contracts;

// @todo: complete
interface TableFilterBuilder
{
    public function addDefinitionFilters(Repository $table, $definition): RepositoryFilterBuilder;

    public function makeFilter(Repository $table, $type, $name, array $options = []): Filter;
}
