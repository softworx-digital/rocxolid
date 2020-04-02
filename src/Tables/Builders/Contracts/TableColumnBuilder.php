<?php

namespace Softworx\RocXolid\Tables\Builders\Contracts;

// @todo: complete
interface TableColumnBuilder
{
    public function addDefinitionColumns(Repository $table, $definition): RepositoryColumnBuilder;

    public function addDefinitionButtons(Repository $table, $definition): RepositoryColumnBuilder;

    public function makeColumn(Repository $table, $type, $name, array $options = []): Column;
}
