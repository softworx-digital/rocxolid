<?php

namespace Softworx\RocXolid\Tables\Traits;

// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Table;
// rocXolid table builder contracts
use Softworx\RocXolid\Tables\Builders\Contracts\TableBuilder;
use Softworx\RocXolid\Tables\Builders\Contracts\TableFilterBuilder;
use Softworx\RocXolid\Tables\Builders\Contracts\TableColumnBuilder;
use Softworx\RocXolid\Tables\Builders\Contracts\TableButtonBuilder;

/**
 * Trait to enable table to be built from definition.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo: consider different approach
 */
trait Buildable
{
    /**
     * Table builder reference.
     *
     * @var \Softworx\RocXolid\Tables\Builders\Contracts\TableBuilder
     */
    private $table_builder = null;

    /**
     * Table filter fields builder reference.
     *
     * @var \Softworx\RocXolid\Tables\Builders\Contracts\TableFilterBuilder
     */
    private $table_filter_builder = null;

    /**
     * Table columns builder reference.
     *
     * @var \Softworx\RocXolid\Tables\Builders\Contracts\TableColumnBuilder
     */
    private $table_column_builder = null;

    /**
     * Table row buttons builder reference.
     *
     * @var \Softworx\RocXolid\Tables\Builders\Contracts\TableButtonBuilder
     */
    private $table_button_builder = null;

    /**
     * Build table filters using definition.
     *
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function buildFilters(): Table
    {
        $this
            ->getTableFilterBuilder()
                ->addDefinitionFilters($this, [
                    'filters' => $this->getFiltersDefinition()
                ]);

        $this
            ->setFilterValues();

        return $this;
    }

    /**
     * Build table columns using definition.
     *
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function buildColumns(): Table
    {
        $this
            ->getTableColumnBuilder()
                ->addDefinitionColumns($this, [
                    'columns' => $this->getColumnsDefinition()
                ]);

        return $this;
    }

    /**
     * Build table row buttons using definition.
     *
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function buildButtons(): Table
    {
        $this
            ->getTableButtonBuilder()
                ->addDefinitionButtons($this, [
                    'buttons' => $this->getButtonsDefinition(),
                ]);

        return $this;
    }

    /**
     * Set the table builder.
     *
     * @param \Softworx\RocXolid\Tables\Builders\Contracts\TableBuilder $table_builder
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function setTableBuilder(TableBuilder $table_builder): Table
    {
        $this->table_builder = $table_builder;

        return $this;
    }

    /**
     * Get table builder.
     *
     * @return \Softworx\RocXolid\Tables\Builders\Contracts\TableBuilder
     */
    public function getTableBuilder(): TableBuilder
    {
        return $this->table_builder;
    }

    /**
     * Set the table filter builder.
     *
     * @param \Softworx\RocXolid\Tables\Builders\Contracts\TableFilterBuilder $table_column_builder
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function setTableFilterBuilder(TableFilterBuilder $table_filter_builder): Table
    {
        $this->table_filter_builder = $table_filter_builder;

        return $this;
    }

    /**
     * Get table filter builder.
     *
     * @return \Softworx\RocXolid\Tables\Builders\Contracts\TableFilterBuilder
     */
    public function getTableFilterBuilder(): TableFilterBuilder
    {
        return $this->table_filter_builder;
    }

    /**
     * Set the table column builder.
     *
     * @param \Softworx\RocXolid\Tables\Builders\Contracts\TableColumnBuilder $table_column_builder
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function setTableColumnBuilder(TableColumnBuilder $table_column_builder): Table
    {
        $this->table_column_builder = $table_column_builder;

        return $this;
    }

    /**
     * Get table column builder.
     *
     * @return \Softworx\RocXolid\Tables\Builders\Contracts\TableColumnBuilder
     */
    public function getTableColumnBuilder(): TableColumnBuilder
    {
        return $this->table_column_builder;
    }

    /**
     * Set the table button builder.
     *
     * @param \Softworx\RocXolid\Tables\Builders\Contracts\TableButtonBuilder $table_button_builder
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function setTableButtonBuilder(TableButtonBuilder $table_button_builder): Table
    {
        $this->table_button_builder = $table_button_builder;

        return $this;
    }

    /**
     * Get table button builder.
     *
     * @return \Softworx\RocXolid\Tables\Builders\Contracts\TableButtonBuilder
     */
    public function getTableButtonBuilder(): TableButtonBuilder
    {
        return $this->table_button_builder;
    }
}
