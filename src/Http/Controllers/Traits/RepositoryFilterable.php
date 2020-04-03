<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

use Softworx\RocXolid\Http\Requests\CrudRequest;

/**
 *
 */
trait RepositoryFilterable
{
    public function repositoryFilter(CrudRequest $request, $param)//: View
    {
        $repository = $this->getRepository($this->getRepositoryParam($request, $param));
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
