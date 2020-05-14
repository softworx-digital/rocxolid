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
 * Trait to provide success response to a CRUD store request.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ProvidesSuccessCreateResponse
{
    /**
     * Provide generic success response to controller store action.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function successStoreResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form)
    {
        return $request->ajax()
            ? $this->successAjaxStoreResponse($request, $model, $form)
            : $this->successNonAjaxStoreResponse($request, $model, $form);
    }

    /**
     * Provide success response for AJAX store requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function successAjaxStoreResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form)
    {
        return $this->successAjaxResponse($request, $model, $form);
    }

    /**
     * Provide success response for non-AJAX store requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function successNonAjaxStoreResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form)
    {
        return $this->successNonAjaxResponse($request, $model, $form);
    }
}
