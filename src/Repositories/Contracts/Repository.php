<?php

namespace Softworx\RocXolid\Repositories\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\Scopeable;
use Softworx\RocXolid\Repositories\Contracts\Orderable;
use Softworx\RocXolid\Repositories\Contracts\Filterable;
use Softworx\RocXolid\Repositories\Contracts\Paginationable;

/**
 * Repository is responsible for retrieving model data upon ordering and filters.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Repository extends Scopeable, Orderable, Filterable, Paginationable
{
    /**
     * Initialize the repository by providing model type to work with.
     *
     * @param string $model_type
     * @return \Softworx\RocXolid\Repositories\Contracts\Repository
     */
    public function init(string $model_type): Repository;

    /**
     * Retrieve the model assigned to the repository.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel(): Model;

    /**
     * Retrieve the model query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery(): Builder;

    /**
     * Retrieve data set based on scopes, order, filter and paging.
     *
     * @param array $columns
     * @return \Illuminate\Support\Collection
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Retrieve data set count based on scopes, order, filter and paging.
     *
     * @return int
     */
    public function count(): int;

    /**
     * Find model instance with applied scopes and internal filters.
     *
     * @param mixed $key
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find($key, array $columns = ['*']): ?Model;

    /**
     * Find model instance with applied scopes and internal filters by given attribute - column.
     *
     * @param string $column
     * @param mixed $value
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findBy(string $column, $value, array $columns = ['*']): ?Model;

    /**
     * Find model instance with applied scopes and internal filters, throw exception when not found.
     *
     * @param mixed $key
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($key, array $columns = ['*']): Model;
}
