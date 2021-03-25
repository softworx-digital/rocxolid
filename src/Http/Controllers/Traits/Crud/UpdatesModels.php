<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid controller contracts
use Softworx\RocXolid\Http\Controllers\Contracts\Crudable;
// rocXolid forms
use Softworx\RocXolid\Forms\AbstractCrudForm;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid components
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer;

/**
 * Update resource CRUD action.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait UpdatesModels
{
    use Response\ProvidesSuccessUpdateResponse;

    /**
     * Display the specified resource update form.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="update",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function edit(CrudRequest $request, CrudableModel $model)//: View
    {
        $model_viewer_component = $this->getUpdateModelViewerComponent($request, $this->initModel($model));

        return $request->ajax()
            ? $this->editAjax($request, $model, $model_viewer_component)
            : $this->editNonAjax($request, $model, $model_viewer_component);
    }

    /**
     * Display the specified resource update form modal for AJAX requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer $model_viewer_component
     */
    protected function editAjax(CrudRequest $request, CrudableModel $model, CrudModelViewer $model_viewer_component)
    {
        return $this->response
            ->modal($model_viewer_component->fetch('modal.update'))
            ->get();
    }

    /**
     * Display the specified resource update form view for non-AJAX requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer $model_viewer_component
     */
    protected function editNonAjax(CrudRequest $request, CrudableModel $model, CrudModelViewer $model_viewer_component)
    {
        return $this
            ->getDashboard()
            ->setModelViewerComponent($model_viewer_component)
            ->render('model', [
                'model_viewer_template' => 'update'
            ]);
    }

    /**
     * Process the update resource request.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="update",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function update(CrudRequest $request, CrudableModel $model)//: Response
    {
        $this->initModel($model);

        // last time to check if something prevents the model to be updated
        if (!$model->canBeUpdated($request)) {
            throw new \RuntimeException(sprintf('Model [%s]:[%s] cannot be updated', (new \ReflectionClass($model))->getName(), $model->getKey()));
        }

        $form = $this->getForm($request, $model);

        return $form->submit()->isValid()
            ? $this->onUpdateFormValid($request, $model, $form)
            : $this->onUpdateFormInvalid($request, $model, $form);
    }

    /**
     * Action to take when the 'update' form is valid.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onUpdateFormValid(CrudRequest $request, CrudableModel $model, AbstractCrudForm $form)//: Response
    {
        $model = $this->getRepository()->updateModel($model, $form->getFormFieldsValues());

        return $this
            ->onModelUpdated($request, $model, $form)
            ->onModelUpdatedSuccessResponse($request, $model, $form);
    }

    /**
     * Action to take after the model has been updated and saved.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     * @return \Softworx\RocXolid\Http\Controllers\Contracts\Crudable
     */
    protected function onModelUpdated(CrudRequest $request, CrudableModel $model, AbstractCrudForm $form): Crudable
    {
        return $this;
    }

    /**
     * Respond to successful model update.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onModelUpdatedSuccessResponse(CrudRequest $request, CrudableModel $model, AbstractCrudForm $form)//: Response
    {
        return $this->successUpdateResponse($request, $model, $form);
    }

    /**
     * Action to take when the 'update' form was submitted with invalid data.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onUpdateFormInvalid(CrudRequest $request, CrudableModel $model, AbstractCrudForm $form)//: Response
    {
        return $this->errorResponse($request, $model, $form, 'update');
    }

    /**
     * Obtain model viewer to be used for edit/update action.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param string|null $tab
     * @return \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer
     */
    protected function getUpdateModelViewerComponent(CrudRequest $request, CrudableModel $model, ?string $tab = null): CrudModelViewer
    {
        $form = $this->getForm($request, $model, $tab);
        $form_component = $this->getFormComponent($form);

        return $this->getModelViewerComponent($model, $form_component);
    }
}
