<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud\Response;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid forms
use Softworx\RocXolid\Forms\AbstractCrudForm;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to provide success response to a request.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ProvidesSuccessResponse
{
    /**
     * Valid form submit navigation parameters.
     *
     * @var array
     */
    protected static $valid_submit_navigation = [
        'submit-new',
        'submit-show',
        'submit-edit',
    ];

    /**
     * Provide generic success response to controller actions.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     * @return \Illuminate\Http\Response|array
     */
    protected function successResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form)
    {
        return $request->ajax()
            ? $this->successAjaxResponse($request, $model, $form)
            : $this->successNonAjaxResponse($request, $model, $form);
    }

    /**
     * Provide success response for non-AJAX requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     * @return \Illuminate\Http\Response
     * @todo action result notification
     */
    protected function successNonAjaxResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form)//: Response
    {
        // $action = $request->route()->getActionName();
        // $action = $request->route()->getActionMethod();

        return redirect($this->successReponseRoute($request, $model, $form))
            ->with($form->getSessionParam('errors'), $form->getErrors()) // @todo needed?
            ->with($form->getSessionParam('input'), $request->input()); // @todo needed?
    }

    /**
     * Provide success response for AJAX requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     * @return array
     * @todo text result notification upon action
     */
    protected function successAjaxResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form): array
    {
        // $action = $request->route()->getActionName();
        // $action = $request->route()->getActionMethod();

        $model_viewer_component = $this->getModelViewerComponent($model);

        return $this->response
            ->notifySuccess($model_viewer_component->translate('text.updated'))
            ->modalClose($model_viewer_component->getDomId(sprintf('modal-%s', $form->getParam())))
            // ->redirect($this->successReponseRoute($request, $model, $form))
            ->get();
    }

    /**
     * Obtain route for success response upon user request.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     * @return string
     */
    protected function successReponseRoute(CrudRequest $request, Crudable $model, AbstractCrudForm $form): string
    {
        $submit_action = $request->input('_submit-action') ?? 'index';

        if (!in_array($submit_action, static::$valid_submit_navigation)) {
            return $this->successReponseRouteForIndex($model);
        }

        $method = sprintf('successReponseRouteFor%s', Str::studly(str_replace('-', '-and-', $submit_action)));

        if (!method_exists($this, $method)) {
            throw new \InvalidArgumentException(sprintf('Invalid submit action method [%s] in [%s] requested', $method, get_class($this)));
        }

        return $this->$method($model);
    }

    /**
     * Obtain route to route user back to index view after submission.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @return string
     */
    protected function successReponseRouteForIndex(Crudable $model): string
    {
        return $this->getRoute('index');
    }

    /**
     * Obtain route to route user back to create view after submission.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @return string
     */
    protected function successReponseRouteForSubmitAndNew(Crudable $model): string
    {
        return $this->getRoute('create');
    }

    /**
     * Obtain route to route user back to read view after submission.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @return string
     */
    protected function successReponseRouteForSubmitAndShow(Crudable $model): string
    {
        return $this->getRoute('show', $model);
    }

    /**
     * Obtain route to route user back to edit view after submission.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @return string
     */
    protected function successReponseRouteForSubmitAndEdit(Crudable $model): string
    {
        return $this->getRoute('edit', $model);
    }
}
