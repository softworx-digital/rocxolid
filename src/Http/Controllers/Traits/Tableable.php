<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Table;
use Softworx\RocXolid\Tables\Contracts\TableBuilder as TableBuilderContract;
// rocXolid table support
use Softworx\RocXolid\Tables\Support\TableBuilder;
// rocXolid controller contracts
use Softworx\RocXolid\Http\Controllers\Contracts\Tableable as TableableContract;

/**
 * Trait to connect the controller with a table.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Tableable
{
    /**
     * Table container.
     *
     * @var array
     */
    protected $tables = [];

    /**
     * {@inheritDoc}
     * @todo: put this to some kind of (Table)Service?
     */
    public function createTable(string $class, string $param = TableableContract::TABLE_PARAM): Table
    {
        $table = $this->getTableBuilder()->buildTable($this, $class);
        $table->setParam($param);

        return $table;
    }

    /**
     * {@inheritDoc}
     */
    public function setTable(Table $table, string $param = TableableContract::TABLE_PARAM): TableableContract
    {
        if (isset($this->tables[$param])) {
            throw new \InvalidArgumentException(sprintf('Table with given parameter [%s] is already set to [%s]', $param, get_class($this)));
        }

        $this->tables[$param] = $table;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTables(): array
    {
        return $this->tables;
    }

    /**
     * {@inheritDoc}
     */
    public function getTable(string $param = TableableContract::TABLE_PARAM): Table
    {
        if (!$this->hasTableAssigned($param)) {
            $class = $this->getTableClass($param);

            if (!class_exists($class)) {
                throw new \InvalidArgumentException(sprintf('Table class [%s] does not exist.', $class));
            }

            $this->setTable($this->createTable($class, $param), $param);
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
    public function hasTableClass(string $param = TableableContract::TABLE_PARAM): bool
    {
        return class_exists($this->getTableClass($param));
    }

    /**
     * Get table param based on action.
     *
     * @param CrudRequest $request
     * @param string $default
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function getTableParam(CrudRequest $request, string $default = TableableContract::TABLE_PARAM)
    {
        $method = $request->route()->getActionMethod();
        /*
        if ($request->filled('_section'))
        {
            $method = sprintf('%s.%s', $method, $request->_section);

            if (isset($this->table_mapping[$method]))
            {
                return $this->table_mapping[$method];
            }
        }
        */
        if (isset($this->table_mapping[$method])) {
            return $this->table_mapping[$method];
        } elseif (!is_null($default)) {
            return $default;
        } elseif (empty($this->table_mapping)) {
            return TableableContract::TABLE_PARAM;
        }

        throw new \InvalidArgumentException(sprintf('No controller [%s] table mapping for method [%s]', get_class($this), $method));
    }

    /**
     * Get table class to work with according to param.
     *
     * @param string $param
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function getTableClass(string $param = TableableContract::TABLE_PARAM): string
    {
        if (isset(static::$table_param_class) && isset(static::$table_param_class[$param])) {
            return static::$table_param_class[$param];
        } elseif (isset(static::$table_class)) {
            return static::$table_class;
        }

        throw new \UnderflowException(sprintf('No table class set for [%s] param [%s].', get_class($this), $param));
    }

    /**
     * Get table builder.
     *
     * @return \Softworx\RocXolid\Tables\Contracts\TableBuilder
     * @todo: Subject to change - better use bindings.
     */
    protected function getTableBuilder(): TableBuilderContract
    {
        if (!property_exists($this, 'table_builder') || is_null($this->table_builder)) {
            $table_builder = app(TableBuilder::class);

            if (property_exists($this, 'table_builder')) {
                $this->table_builder = $table_builder;
            }
        } elseif (property_exists($this, 'table_builder')) {
            $table_builder = $this->table_builder;
        }

        return $table_builder;
    }
}
