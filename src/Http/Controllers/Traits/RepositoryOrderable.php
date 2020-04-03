<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

use Softworx\RocXolid\Http\Requests\CrudRequest;

/**
 *
 */
trait RepositoryOrderable
{
    public function repositoryOrderBy(CrudRequest $request, $param, $order_by_column, $order_by_direction)//: View
    {
        $repository = $this->getRepository($this->getRepositoryParam($request, $param));
        $repository->setOrderBy($order_by_column, $order_by_direction);
        $table_component = $this->getTableComponent($repository);

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
