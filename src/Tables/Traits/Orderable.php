<?php

namespace Softworx\RocXolid\Tables\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Columns\Contracts\Column;
use Softworx\RocXolid\Tables\Contracts\Orderable as OrderableContract;

/**
 * Enables data table to set records ordering.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo support for non(cross)-model columns ordering
 *  possible approach: pass OrderByProvider to repository and define providesOrderByModel method, that will cause joining if non primary model order request
 */
trait Orderable
{
    /**
     * {@inheritDoc}
     */
    public function setOrderBy(string $column_name, string $direction): OrderableContract
    {
        if ($this->isValidRequest($column_name, $direction)) {
            $this->getRequest()->session()->put($this->getSessionKey(static::ORDER_BY_SESSION_PARAM), collect([
                'column' => $column_name,
                'direction' => $direction,
            ]));
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isOrderColumn(Column $column): bool
    {
        return $column->getName() === $this->getOrderByColumn();
    }

    /**
     * {@inheritDoc}
     */
    public function isOrderDirection(string $direction): bool
    {
        return $direction === $this->getOrderByDirection();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrderByRoute(string $column, string $direction): string
    {
        return $this->getController()->getRoute('tableOrderBy', [
            'param' => $this->getParam(),
            'order_by_column' => $column,
            'order_by_direction' => $direction,
        ]);
    }

    /**
     * Check if table ordering has been set by user.
     *
     * @return bool
     */
    protected function isSetCustomOrder(): bool
    {
        return $this->getRequest()->session()->exists($this->getSessionKey(static::ORDER_BY_SESSION_PARAM))
            && $this->getRequest()->session()->has($this->getSessionKey(static::ORDER_BY_SESSION_PARAM));
    }

    /**
     * Retrieve table ordering column.
     *
     * @return string
     */
    public function getOrderByColumn(): string
    {
        return $this->isSetCustomOrder()
            ? $this->getRequest()->session()->get($this->getSessionKey(static::ORDER_BY_SESSION_PARAM))->get('column')
            : $this->getDefaultOrderByColumn();
    }

    /**
     * Retrieve table ordering direction.
     *
     * @return string
     */
    protected function getOrderByDirection(): string
    {
        return $this->isSetCustomOrder()
            ? $this->getRequest()->session()->get($this->getSessionKey(static::ORDER_BY_SESSION_PARAM))->get('direction')
            : $this->getDefaultOrderByDirection();
    }

    /**
     * Check if table ordering request is valid.
     *
     * @return bool
     */
    protected function isValidRequest(string $column_name, string $direction): bool
    {
        return Schema::hasColumn($this->getController()->getRepository()->getModel()->getTable(), $column_name)
            && in_array($direction, ['asc', 'desc']);
    }

    /**
     * Get default order column for repository data.
     *
     * @return string
     */
    protected function getDefaultOrderByColumn(): string
    {
        return $this->getController()->getRepository()->getModel()->getKeyName();
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
