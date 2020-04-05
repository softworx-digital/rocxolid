<?php

namespace Softworx\RocXolid\Tables\Builders\Contracts;

use Softworx\RocXolid\Tables\Contracts\Table;
use Softworx\RocXolid\Tables\Filters\Contracts\Filter;

/**
 * Builds data table filter fields.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo: make more abstract
 */
interface TableFilterBuilder
{
    /**
     * Add filters to table defined by an array configuration.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Table $table
     * @param array $definition
     * @return \Softworx\RocXolid\Tables\Builders\Contracts
     */
    public function addDefinitionFilters(Table $table, array $definition): TableFilterBuilder;

    /**
     * Builds filter element with given type, name and options.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Table $table
     * @param string $type
     * @param string $name
     * @param array $options
     * @return \Softworx\RocXolid\Tables\Filters\Contracts\Filter
     */
    public function makeFilter(Table $table, string $type, string $name, array $options = []): Filter;
}
