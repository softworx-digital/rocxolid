<?php

namespace Softworx\RocXolid\Tables\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
// rocXolid repository contracts
use Softworx\RocXolid\Tables\Contracts\Column;
use Softworx\RocXolid\Tables\Contracts\Orderable as OrderableContract;

/**
 * Trait to enable model data ordering.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Orderable
{
    public function setOrderBy(string $column_name, string $direction): OrderableContract
    {
        if (Schema::hasColumn($this->getModel()->getTable(), $column_name) && in_array($direction, ['asc', 'desc'])) {
            $this->getRequest()->session()->put($this->getSessionParam(static::ORDER_BY_SESSION_PARAM), collect([
                'column' => $column_name,
                'direction' => $direction,
            ]));
        }

        return $this;
    }

    public function isOrderColumn(Column $column): bool
    {
        return $column->getName() === $this->getOrderByColumn();
    }

    public function isOrderDirection(string $direction): bool
    {
        return $direction === $this->getOrderByDirection();
    }

    protected function isSetCustomOrder(): bool
    {
        return $this->getRequest()->session()->exists($this->getSessionParam(static::ORDER_BY_SESSION_PARAM))
            && $this->getRequest()->session()->has($this->getSessionParam(static::ORDER_BY_SESSION_PARAM));
    }

    protected function getOrderByColumn(): string
    {
        return $this->isSetCustomOrder()
            ? $this->getRequest()->session()->get($this->getSessionParam(static::ORDER_BY_SESSION_PARAM))->get('column')
            : $this->getController()->getRepository()->getOrderByColumn();
    }

    protected function getOrderByDirection(): string
    {
        return $this->isSetCustomOrder()
            ? $this->getRequest()->session()->get($this->getSessionParam(static::ORDER_BY_SESSION_PARAM))->get('direction')
            : $this->getController()->getRepository()->getOrderByDirection();
    }
}
