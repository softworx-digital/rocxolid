<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud\Response;

// use Symfony\Component\HttpFoundation\Response;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid forms
use Softworx\RocXolid\Forms\AbstractCrudForm;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to provide success response to a CRUD update request.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ProvidesSuccessUpdateResponse
{
    /**
     * Provide generic success response to controller update action.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function successUpdateResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form)
    {
        return $request->ajax()
            ? $this->successAjaxUpdateResponse($request, $model, $form)
            : $this->successNonAjaxUpdateResponse($request, $model, $form);
    }

    /**
     * Provide success response for AJAX update requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function successAjaxUpdateResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form)
    {
        return $this->successAjaxResponse($request, $model, $form);
    }

    /**
     * Provide success response for non-AJAX update requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function successNonAjaxUpdateResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form)
    {
        return $this->successNonAjaxResponse($request, $model, $form);
    }
}
