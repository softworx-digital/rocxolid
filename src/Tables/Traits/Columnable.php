<?php

namespace Softworx\RocXolid\Tables\Traits;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Tables\Contracts\Columnable as ColumnableContract;
use Softworx\RocXolid\Tables\Columns\Contracts\Column;

/**
 * Enables to assign columns.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Columnable
{
    /**
     * Assigned columns collection.
     *
     * @var \Illuminate\Support\Collection
     */
    private $_columns;

    /**
     * {@inheritDoc}
     */
    public function addColumn(Column $column): ColumnableContract
    {
        $this->getColumns()->put($column->getName(), $column);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setColumns(Collection $columns): ColumnableContract
    {
        $this->_columns = $columns;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getColumns(): Collection
    {
        if (!isset($this->_columns)) {
            $this->_columns = collect();
        }

        return $this->_columns;
    }

    /**
     * {@inheritDoc}
     */
    public function getColumn(string $name): Column
    {
        if ($this->getColumns()->has($name)) {
            return $this->getColumns()->get($name);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid column (name) [%s] requested', $name));
        }
    }
}
