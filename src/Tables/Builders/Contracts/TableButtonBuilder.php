<?php

namespace Softworx\RocXolid\Tables\Builders\Contracts;

use Softworx\RocXolid\Tables\Contracts\Table;
use Softworx\RocXolid\Tables\Buttons\Contracts\Button;

/**
 * Builds data table row buttons.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo: make more abstract
 */
interface TableButtonBuilder
{
    /**
     * Add buttons to table rows defined by an array configuration.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Table $table
     * @param array $definition
     * @return \Softworx\RocXolid\Tables\Builders\Contracts
     */
    public function addDefinitionButtons(Table $table, array $definition): TableButtonBuilder;

    /**
     * Builds button element with given type, name and options.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Table $table
     * @param string $type
     * @param string $name
     * @param array $options
     * @return \Softworx\RocXolid\Tables\Buttons\Contracts\Button
     */
    public function makeButton(Table $table, string $type, string $name, array $options = []): Button;
}
