<?php

namespace Softworx\RocXolid\Repositories\Traits;

use Schema;
use Softworx\RocXolid\Repositories\Contracts\Column;
use Softworx\RocXolid\Repositories\Contracts\Orderable as OrderableContract;

trait Orderable
{
    protected static $default_order = [
        'column' => 'id',
        'direction' => 'asc',
    ];

    public function setOrderBy(string $column_name, string $direction)
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

    public function isOrderColumn(Column $column)
    {
        return $column->getName() == $this->getOrderByColumn();
    }

    public function isOrderDirection(string $direction)
    {
        return $direction == $this->getOrderByDirection();
    }

    protected function isSetCustomOrder(): bool
    {
        return $this->getRequest()->session()->exists($this->getSessionParam(static::ORDER_BY_SESSION_PARAM))
            && $this->getRequest()->session()->has($this->getSessionParam(static::ORDER_BY_SESSION_PARAM));
    }

    protected function applyOrder(): OrderableContract
    {
        $this->query = $this->getQuery()->orderBy(sprintf('%s.%s', $this->getModel()->getTable(), $this->getOrderByColumn()), $this->getOrderByDirection());

        return $this;
    }

    protected function getOrderByColumn()
    {
        return $this->isSetCustomOrder() ? $this->getRequest()->session()->get($this->getSessionParam(static::ORDER_BY_SESSION_PARAM))->get('column') : static::$default_order['column'];
    }

    protected function getOrderByDirection()
    {
        return $this->isSetCustomOrder() ? $this->getRequest()->session()->get($this->getSessionParam(static::ORDER_BY_SESSION_PARAM))->get('direction') : static::$default_order['direction'];
    }
}
