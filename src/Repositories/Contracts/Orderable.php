<?php

namespace Softworx\RocXolid\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Enables ordering to repository query.
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
     * @param string $column_name Column to order.
     * @param string $direction Order direction.
     * @param \Illuminate\Database\Eloquent\Model|null $model Model to be used for table ordering.
     * @return \Softworx\RocXolid\Repositories\Contracts\Orderable
     * @throws \InvalidArgumentException If invalid data provided.
     */
    public function setOrderBy(string $column_name, string $direction, ?Model $model = null): Orderable;
}
