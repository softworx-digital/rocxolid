<?php

namespace Softworx\RocXolid\Tables\Builders\Contracts;

use Softworx\RocXolid\Tables\Contracts\Table;
use Softworx\RocXolid\Tables\Contracts\Column;

/**
 * Builds data table row columns.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo: make more abstract
 */
interface TableColumnBuilder
{
    /**
     * Add columns to table rows defined by an array configuration.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Table $table
     * @param array $definition
     * @return \Softworx\RocXolid\Tables\Builders\Contracts
     */
    public function addDefinitionColumns(Table $table, array $definition): TableColumnBuilder;

    /**
     * Builds column element with given type, name and options.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Table $table
     * @param string $type
     * @param string $name
     * @param array $options
     * @return \Softworx\RocXolid\Tables\Contracts\Column
     */
    public function makeColumn(Table $table, string $type, string $name, array $options = []): Column;
}
