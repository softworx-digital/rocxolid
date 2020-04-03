<?php

namespace Softworx\RocXolid\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Enables ordering application to repository query.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Orderable
{
    /**
     * Set repository ordering.
     *
     * @param string $column_name
     * @param string $direction
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return \Softworx\RocXolid\Repositories\Contracts\Orderable
     */
    public function setOrderBy(string $column_name, string $direction, ?Model $model = null): Orderable;

    /**
     * Get model to get table for ordering.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getOrderByModel(): Model;

    /**
     * Get order column for repository data.
     *
     * @return string
     */
    public function getOrderByColumn(): string;

    /**
     * Get fully qualified order column for repository data.
     *
     * @return string
     */
    public function getFullyQualifiedOrderByColumn(): string;

    /**
     * Get order direction for repository data.
     *
     * @return string
     */
    public function getOrderByDirection(): string;
}
