<?php

namespace Softworx\RocXolid\Tables\Contracts;

use Softworx\RocXolid\Tables\Columns\Contracts\Column;

/**
 * Enables data table to set records ordering.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Orderable
{
    const ORDER_BY_SESSION_PARAM = 'order-by';

    /**
     * Set table ordering.
     *
     * @param string $column_name
     * @param string $direction
     * @return \Softworx\RocXolid\Tables\Contracts
     */
    public function setOrderBy(string $column_name, string $direction): Orderable;

    /**
     * Check if table is ordered by given column.
     *
     * @param Column $column
     * @return boolean
     */
    public function isOrderColumn(Column $column): bool;

    /**
     * Check if table is ordered by given direction.
     *
     * @param string $direction
     * @return boolean
     */
    public function isOrderDirection(string $direction): bool;

    /**
     * Retrieve route for ordering table by given column and direction.
     *
     * @param string $column
     * @param string $direction
     * @return string
     */
    public function getOrderByRoute(string $column, string $direction): string;
}
