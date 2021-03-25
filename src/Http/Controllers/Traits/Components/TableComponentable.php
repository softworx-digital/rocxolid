<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Components;

// rocXolid tables
use Softworx\RocXolid\Tables\Contracts\Table;
// rocXolid components
use Softworx\RocXolid\Components\Tables\CrudTable as CrudTableComponent;

/**
 * Helper trait to obtain table component.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait TableComponentable
{
    protected static $table_component_type = CrudTableComponent::class;

    /**
     * Retrieve model data table component to show.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Table $table
     * @return \Softworx\RocXolid\Components\Tables\CrudTable
     */
    public function getTableComponent(Table $table): CrudTableComponent
    {
        return static::$table_component_type::build($this, $this)
            ->setTable($table);
    }
}
