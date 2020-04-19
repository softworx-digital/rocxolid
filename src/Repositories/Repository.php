<?php

namespace Softworx\RocXolid\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\Repository as RepositoryContract;

/**
 * Repository is responsible for retrieving model data upon ordering and filters.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class Repository implements RepositoryContract
{
    use Traits\Scopeable;
    use Traits\Orderable;
    use Traits\Filterable;
    use Traits\Paginationable;

    /**
     * Model reference to work with.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $query_model;

    /**
     * {@inheritDoc}
     */
    public function init(string $model_type): RepositoryContract
    {
        if (!$this->validateModelType($model_type)) {
            throw new \InvalidArgumentExcption(sprintf('Class [%s] is not valid to use with [%s]', $model_type, static::class));
        }

        $this->query_model = app($model_type);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getModel(): Model
    {
        return $this->query_model;
    }

    /**
     * {@inheritDoc}
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this
            ->getCollectionQuery()
            ->get($columns);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return $this
            ->getCollectionQuery()
            ->count();
    }

    /**
     * {@inheritDoc}
     */
    public function find($key, array $columns = ['*']): ?Model
    {
        return $this->findBy($this->getModel()->getKeyName(), $key, $columns);
    }

    /**
     * {@inheritDoc}
     */
    public function findBy(string $column, $value, array $columns = ['*']): ?Model
    {
        return $this
            ->getQuery()
            ->where($column, '=', $value)
            ->first($columns);
    }

    /**
     * {@inheritDoc}
     */
    public function findOrFail($key, array $columns = ['*']): Model
    {
        return $this
            ->getQuery()
            ->findOrFail($key, $columns);
    }

    /**
     * Initialize model query for retrieving single model data.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getQuery(): Builder
    {
        // rebooting, because some traits might not been
        // properly booted before (eg. ProtectsRoot)
        $this->query_model::boot();

        $query = $this->query_model->query();

        $this
            ->applyScopes($query)
            ->applyIntenalFilters($query);

        return $query;
    }

    /**
     * Initialize model query for retrieving collection of data.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getCollectionQuery(): Builder
    {
        $query = $this->getQuery();

        $this
            ->applyOrder($query)
            ->applyFilters($query);

        return $query;
    }

    /**
     * Apply internal repository filters to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @return \Softworx\RocXolid\Repositories\Contracts\Repository
     */
    protected function applyIntenalFilters(Builder &$query): RepositoryContract
    {
        return $this;
    }

    /**
     * Validate if provided model class is suitable for repository.
     *
     * @param string $model_type
     * @return bool
     */
    protected function validateModelType(string $model_type): bool
    {
        return (new \ReflectionClass($model_type))->isSubclassOf(Model::class);
    }
}
