<?php

namespace Softworx\RocXolid\Tables\Builders\Contracts;

// @todo: finish
interface TableFilterBuilder
{
    public function addDefinitionFilters(Repository $table, $definition): RepositoryFilterBuilder;

    public function makeFilter(Repository $table, $type, $name, array $options = []): Filter;
}
