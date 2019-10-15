<?php

namespace Softworx\RocXolid\Repositories\Traits;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Repositories\Contracts\Column;
use Softworx\RocXolid\Repositories\Contracts\Columnable as ColumnableContract;

trait Columnable
{
    private $_columns = null;

    public function addColumn(Column $column): ColumnableContract
    {
        $this->getColumns()->put($column->getName(), $column);

        return $this;
    }

    public function setColumns($columns): ColumnableContract
    {
        $this->_columns = new Collection($columns);

        return $this;
    }

    public function getColumns(): Collection
    {
        if (is_null($this->_columns)) {
            $this->_columns = new Collection();
        }

        return $this->_columns;
    }

    public function getColumn($name): Column
    {
        if ($this->getColumns()->has($name)) {
            return $this->getColumns()->get($name);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid column (name) [%s] requested', $name));
        }
    }
}
