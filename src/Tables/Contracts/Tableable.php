<?php

namespace Softworx\RocXolid\Tables\Contracts;

use Illuminate\Support\Collection;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Table;

/**
 * Interface to connect the data table with a container.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Tableable
{
    /**
     * Default param for table mappings.
     */
    const TABLE_PARAM = 'index';

    /**
     * Set the table reference to data table pool.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Table $table
     * @param string $param
     * @return \Softworx\RocXolid\Tables\Contracts\Tableable
     */
    public function setTable(Table $table, string $param = self::TABLE_PARAM): Tableable;

    /**
     * Get all assigned tables
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTables(): Collection;

    /**
     * Retrieve data table instance upon request.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param string|null $param Table param.
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     * @throws \RuntimeException
     */
    public function getTable(CrudRequest $request, ?string $param = null): Table;

    /**
     * Check if the param is already bound.
     *
     * @param string $param
     * @return bool
     */
    public function hasTableAssigned(string $param = self::TABLE_PARAM): bool;

    /**
     * Get data table class name mapped to a parameter.
     *
     * @param string $param
     * @return string
     */
    public function getTableMappingType(string $param): string;
}
