<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions\Response;

// use Symfony\Component\HttpFoundation\Response;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid forms
use Softworx\RocXolid\Forms\AbstractCrudForm;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to provide success response to a (CRUD) clone request.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ProvidesSuccessCloneResponse
{
    /**
     * Provide generic success response to controller clone action.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function successCloneResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form)
    {
        return $request->ajax()
            ? $this->successAjaxCloneResponse($request, $model, $form)
            : $this->successNonAjaxCloneResponse($request, $model, $form);
    }

    /**
     * Provide success response for AJAX clone requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function successAjaxCloneResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form)
    {
        return $this->successAjaxResponse($request, $model, $form);
    }

    /**
     * Provide success response for non-AJAX clone requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function successNonAjaxCloneResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form)
    {
        return $this->successNonAjaxResponse($request, $model, $form);
    }
}
