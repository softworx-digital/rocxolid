<?php

namespace Softworx\RocXolid\Repositories\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\Orderable as OrderableContract;

/**
 * Trait to enable model data ordering.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Orderable
{
    /**
     * Model to get table for ordering.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $order_by_model;

    /**
     * Order column for repository data.
     *
     * @var string
     */
    private $order_by_column;

    /**
     * Order direction for repository data.
     *
     * @var string
     */
    private $order_by_direction;

    /**
     * {@inheritDoc}
     */
    public function setOrderBy(string $column_name, string $direction, ?Model $model = null): OrderableContract
    {
        $model = $model ?? $this->query_model;

        if (Schema::hasColumn($model->getTable(), $column_name) && in_array($direction, ['asc', 'desc'])) {
            $this->setOrderByModel($model);
            $this->setOrderByColumn($column_name);
            $this->setOrderByDirection($direction);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getOrderByModel(): Model
    {
        return $this->order_by_model ?? $this->query_model;
    }

    /**
     * {@inheritDoc}
     */
    public function getOrderByColumn(): string
    {
        return $this->order_by_column ?? $this->getDefaultOrderByColumn();
    }

    /**
     * {@inheritDoc}
     */
    public function getFullyQualifiedOrderByColumn(): string
    {
        return sprintf('%s.%s', $this->getOrderByModel()->getTable(), $this->getOrderByColumn());
    }

    /**
     * {@inheritDoc}
     */
    public function getOrderByDirection(): string
    {
        return $this->order_by_direction ?? $this->getDefaultOrderByDirection();
    }

    /**
     * Apply column ordering to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @return \Softworx\RocXolid\Repositories\Contracts\Orderable
     */
    protected function applyOrder(Builder &$query): OrderableContract
    {
        $query = $query->orderBy($this->getFullyQualifiedOrderByColumn(), $this->getOrderByDirection());

        return $this;
    }

    /**
     * Set model to get table for ordering.
     *
     * @param \Illuminate\Database\Eloquent\Model $order_by_model
     * @return \Softworx\RocXolid\Repositories\Contracts\Orderable
     */
    protected function setOrderByModel(Model $order_by_model): OrderableContract
    {
        $this->order_by_model = $order_by_model;

        return $this;
    }

    /**
     * Set order column for repository data.
     *
     * @param string $order_by_column
     * @return \Softworx\RocXolid\Repositories\Contracts\Orderable
     */
    protected function setOrderByColumn(string $order_by_column): OrderableContract
    {
        $this->order_by_column = $order_by_column;

        return $this;
    }

    /**
     * Set order direction for repository data.
     *
     * @param string $order_by_direction
     * @return \Softworx\RocXolid\Repositories\Contracts\Orderable
     */
    protected function setOrderByDirection(string $order_by_direction): OrderableContract
    {
        $this->order_by_direction = $order_by_direction;

        return $this;
    }

    /**
     * Get default order column for repository data.
     *
     * @return string
     */
    protected function getDefaultOrderByColumn(): string
    {
        return $this->getOrderByModel()->getKeyName();
    }

    /**
     * Get default order direction for repository data.
     *
     * @return string
     */
    protected function getDefaultOrderByDirection(): string
    {
        return 'asc';
    }
}
