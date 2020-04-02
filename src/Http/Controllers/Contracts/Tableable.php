<?php

namespace Softworx\RocXolid\Http\Controllers\Contracts;

use Softworx\RocXolid\Tables\Contracts\Table;

/**
 * Interface to connect the controller with a table.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Tableable
{
    const TABLE_PARAM = 'index';

    public function createTable(string $type, string $param = self::TABLE_PARAM): Table;

    public function setTable(Table $table, string $param = self::TABLE_PARAM): Tableable;

    public function getTables(): array;

    public function getTable(string $param = self::TABLE_PARAM): Table;

    public function hasTableAssigned(string $param = self::TABLE_PARAM): bool;

    public function hasTableClass(string $param = self::TABLE_PARAM): bool;
}
