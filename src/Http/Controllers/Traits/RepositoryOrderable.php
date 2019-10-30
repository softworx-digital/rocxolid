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
        $repository_component = $this->getRepositoryComponent($repository);

        if ($request->ajax()) {
            return $this->response
                ->replace($repository_component->getDomId('results'), $repository_component->fetch('include.results'))
                ->get();
        } else {
            return $this
                ->getDashboard()
                ->setRepositoryComponent($repository_component)
                ->render('index');
        }
    }
}
