<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

// @todo upratat
use App;
use ViewHelper;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Repositories\Contracts\Repositoryable;
use Softworx\RocXolid\Http\Requests\CrudRequest;
use Softworx\RocXolid\Components\AbstractActiveComponent;
use Softworx\RocXolid\Components\General\Message;
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;
use Softworx\RocXolid\Communication\Contracts\AjaxResponse;
use Softworx\RocXolid\Forms\AbstractCrudForm as AbstractCrudForm;

/**
 *
 */
trait RepositoryFilterable
{
    public function repositoryFilter(CrudRequest $request, $param)//: View
    {
        $repository = $this->getRepository($this->getRepositoryParam($request, $param));
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
