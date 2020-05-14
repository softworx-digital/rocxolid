<?php

namespace Softworx\RocXolid\Tables\Services\Contracts;

// rocXolid service contracts
use Softworx\RocXolid\Services\Contracts\ConsumerService;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Table;

/**
 * Serves to retrieve and manipulate tables.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface TableService extends ConsumerService
{
    /**
     * Create data table based on provided parameter which is set back to the created table.
     *
     * @param string $param
     * @param string|null $type
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function createTable(string $param, ?string $type = null): Table;
}
