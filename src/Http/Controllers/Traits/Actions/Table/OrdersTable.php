<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions\Table;

use Softworx\RocXolid\Http\Requests\CrudRequest;

/**
 * Action to set resource data table ordering.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait OrdersTable
{
    /**
     * Set resource data table ordering column and direction.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param string $param Table parameter.
     * @param string $order_by_column Ordering column.
     * @param string $order_by_direction Ordering direction.
     * @return void
     */
    public function tableOrderBy(CrudRequest $request, string $param, string $order_by_column, string $order_by_direction)//: View
    {
        $table = $this
            ->getTable($request)
            ->setOrderBy($order_by_column, $order_by_direction);

        $table_component = $this->getTableComponent($table);

        if ($request->ajax()) {
            return $this->response
                ->replace($table_component->getDomId('results'), $table_component->fetch('include.results'))
                ->get();
        } else {
            return $this
                ->getDashboard()
                ->setTableComponent($table_component)
                ->render('index');
        }
    }
}
