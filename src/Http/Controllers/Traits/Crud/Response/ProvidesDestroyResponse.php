<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud\Response;

// use Symfony\Component\HttpFoundation\Response;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to provide response to a CRUD destroy request.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ProvidesDestroyResponse
{
    /**
     * Redirect to model's index view.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    protected function destroyResponse(CrudRequest $request, Crudable $model)//: Response
    {
        if ($request->ajax()) {
            return $this->response->redirect($this->getRoute('index'))->get();
        } else {
            return redirect($this->getRoute('index'));
        }
    }
}
