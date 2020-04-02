<?php

namespace Softworx\RocXolid\Tables\Contracts;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Tables\Contracts\Column;

/**
 * Enables to assign columns.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Columnable
{
    /**
     * Add column to container.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Column $column
     * @return \Softworx\RocXolid\Tables\Contracts\Columnable
     */
    public function addColumn(Column $column): Columnable;

    /**
     * Replace the columns with new columns collection.
     *
     * @param \Illuminate\Support\Collection $columns
     * @return \Softworx\RocXolid\Tables\Contracts\Columnable
     */
    public function setColumns(Collection $columns): Columnable;

    /**
     * Get assigned columns.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getColumns(): Collection;

    /**
     * Get single column by its name.
     *
     * @param string $name
     * @return \Softworx\RocXolid\Tables\Contracts\Column
     */
    public function getColumn(string $name): Column;
}
