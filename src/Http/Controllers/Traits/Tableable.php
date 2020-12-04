<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

use Illuminate\Support\Collection;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Tableable as TableableContract;
use Softworx\RocXolid\Tables\Contracts\Table;
// rocXolid controller traits
use Softworx\RocXolid\Http\Controllers\Traits\ElementMappable;

/**
 * Trait to connect the controller with a data table.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Tableable
{
    use ElementMappable;

    /**
     * Tables container.
     *
     * @var array
     */
    protected $tables = [];

    /**
     * {@inheritDoc}
     */
    public function setTable(Table $table, string $param = TableableContract::TABLE_PARAM): TableableContract
    {
        if ($this->hasTableAssigned($param)) {
            throw new \InvalidArgumentException(sprintf('Table with given parameter [%s] is already set to [%s]', $param, get_class($this)));
        }

        $this->tables[$param] = $table;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTables(): Collection
    {
        return collect($this->tables);
    }

    /**
     * {@inheritDoc}
     */
    public function getTable(CrudRequest $request, ?string $param = null): Table
    {
        $param = $param ?? $this->getMappingParam($request, 'table', TableableContract::TABLE_PARAM);

        if (!$this->hasTableAssigned($param)) {
            $this->setTable($this->tableService()->createTable($param), $param);
        }

        return $this->tables[$param];
    }

    /**
     * {@inheritDoc}
     */
    public function hasTableAssigned(string $param = TableableContract::TABLE_PARAM): bool
    {
        return isset($this->tables[$param]);
    }

    /**
     * {@inheritDoc}
     */
    public function getTableMappingType(string $param): string
    {
        return $this->getMappingType('table', $param);
    }
}
