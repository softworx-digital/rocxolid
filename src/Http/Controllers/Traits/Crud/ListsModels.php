<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud;

use Softworx\RocXolid\Http\Requests\CrudRequest;

/**
 * List resource CRUD action.
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
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     */
    public function index(CrudRequest $request)//: View
    {
        $table_component = $this->getTableComponent($this->getTable($request));

        if ($request->ajax()) {
            return $this->response
                ->replace($table_component->getDomId(), $table_component->fetch())
                ->get();
        } else {
            return $this
                ->getDashboard()
                ->setTableComponent($table_component)
                ->render('index');
        }
    }
}
