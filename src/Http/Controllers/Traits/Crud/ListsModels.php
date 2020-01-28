<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud;

use Softworx\RocXolid\Http\Requests\CrudRequest;

/**
 * Trait to list resource.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ListsModels
{
    /**
     * Display a listing of the resource.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="read-only",policy_ability="viewAny",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     */
    public function index(CrudRequest $request)//: View
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));
        $repository_component = $this->getRepositoryComponent($repository);

        if ($request->ajax()) {
            return $this->response
                ->replace($repository_component->getDomId(), $repository_component->fetch())
                ->get();
        } else {
            return $this
                ->getDashboard()
                ->setRepositoryComponent($repository_component)
                ->render('index');
        }
    }
}
