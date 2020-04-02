<?php

namespace Softworx\RocXolid\Repositories\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\Column;
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
    protected static $default_order = [
        'column' => 'id',
        'direction' => 'asc',
    ];

    public function setOrderBy(string $column_name, string $direction): OrderableContract
    {
        if (Schema::hasColumn($this->getModel()->getTable(), $column_name)
            && in_array($direction, ['asc', 'desc'])) {
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

    protected function applyOrder(EloquentBuilder &$query): OrderableContract
    {
        $this->query = $this->getQuery()->orderBy(sprintf('%s.%s', $this->getModel()->getTable(), $this->getOrderByColumn()), $this->getOrderByDirection());

        return $this;
    }

    protected function getOrderByColumn(): string
    {
        return $this->isSetCustomOrder() ? $this->getRequest()->session()->get($this->getSessionParam(static::ORDER_BY_SESSION_PARAM))->get('column') : static::$default_order['column'];
    }

    protected function getOrderByDirection(): string
    {
        return $this->isSetCustomOrder() ? $this->getRequest()->session()->get($this->getSessionParam(static::ORDER_BY_SESSION_PARAM))->get('direction') : static::$default_order['direction'];
    }
}
