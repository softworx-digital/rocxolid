<?php

namespace Softworx\RocXolid\Tables\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Paramable;
use Softworx\RocXolid\Contracts\Requestable;
use Softworx\RocXolid\Contracts\Controllable;
use Softworx\RocXolid\Contracts\Optionable;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Columnable;
use Softworx\RocXolid\Tables\Contracts\Buttonable;
use Softworx\RocXolid\Tables\Contracts\Orderable;
use Softworx\RocXolid\Tables\Contracts\Filterable;
use Softworx\RocXolid\Tables\Contracts\Paginationable;
// rocXolid table builder contracts
use Softworx\RocXolid\Tables\Builders\Contracts\TableBuilder;
use Softworx\RocXolid\Tables\Builders\Contracts\TableFilterBuilder;
use Softworx\RocXolid\Tables\Builders\Contracts\TableColumnBuilder;
use Softworx\RocXolid\Tables\Builders\Contracts\TableButtonBuilder;

/**
 * Provides data table assigned to a controller.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Table extends Paramable, Requestable, Controllable, Optionable, Columnable, Buttonable, Orderable, Filterable, Paginationable
{
    /**
     * Table initializer.
     *
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function init(): Table;

    /**
     * Obtain data from connected controller's repository.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getData(): LengthAwarePaginator;

    /**
     * Set the table builder.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\TableBuilder $table_builder
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function setTableBuilder(TableBuilder $table_builder): Table;

    /**
     * Get table builder.
     *
     * @return \Softworx\RocXolid\Tables\Contracts\TableBuilder
     */
    public function getTableBuilder(): TableBuilder;

    /**
     * Set the table column builder.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\TableColumnBuilder $form_field_builder
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function setTableColumnBuilder(TableColumnBuilder $table_column_builder): Table;

    /**
     * Get table column builder.
     *
     * @return \Softworx\RocXolid\Tables\Contracts\TableColumnBuilder
     */
    public function getTableColumnBuilder(): TableColumnBuilder;

    /**
     * Set the table column builder.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\TableButtonBuilder $table_button_builder
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function setTableButtonBuilder(TableButtonBuilder $table_button_builder): Table;

    /**
     * Get table column builder.
     *
     * @return \Softworx\RocXolid\Tables\Contracts\TableButtonBuilder
     */
    public function getTableButtonBuilder(): TableButtonBuilder;

    /**
     * Set the table column builder.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\TableFilterBuilder $table_filter_builder
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function setTableFilterBuilder(TableFilterBuilder $table_filter_builder): Table;

    /**
     * Get table column builder.
     *
     * @return \Softworx\RocXolid\Tables\Contracts\TableFilterBuilder
     */
    public function getTableFilterBuilder(): TableFilterBuilder;
}
