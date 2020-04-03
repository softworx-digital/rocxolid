<?php

namespace Softworx\RocXolid\Tables\Builders\Contracts;

// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Table;
use Softworx\RocXolid\Tables\Contracts\Tableable;

/**
 * Table builder and dependencies connector.
 * Provides convenient way to build a data table.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface TableBuilder
{
    /**
     * Get instance of the table which can be modified.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Tableable $container
     * @param string $type
     * @param array $custom_options
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function buildTable(Tableable $container, string $type, array $custom_options = []): Table;
}
