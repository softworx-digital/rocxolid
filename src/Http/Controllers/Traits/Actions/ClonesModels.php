<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions;

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
 * Clone resource (CRUD) action.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ClonesModels
{
    use Response\ProvidesSuccessCloneResponse;

    /**
     * Display the specified resource clone form.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="create",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function duplicate(CrudRequest $request, CrudableModel $model)//: View
    {
        $model_viewer_component = $this->getCloneModelViewerComponent($request, $model);

        return $request->ajax()
            ? $this->duplicateAjax($request, $model, $model_viewer_component)
            : $this->duplicateNonAjax($request, $model, $model_viewer_component);
    }

    /**
     * Display the specified resource clone form modal for AJAX requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer $model_viewer_component
     */
    protected function duplicateAjax(CrudRequest $request, CrudableModel $model, CrudModelViewer $model_viewer_component)
    {
        return $this->response
            ->modal($model_viewer_component->fetch('modal.clone'))
            ->get();
    }

    /**
     * Display the specified resource clone form view for non-AJAX requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer $model_viewer_component
     */
    protected function duplicateNonAjax(CrudRequest $request, CrudableModel $model, CrudModelViewer $model_viewer_component)
    {
        return $this
            ->getDashboard()
            ->setModelViewerComponent($model_viewer_component)
            ->render('model', [
                'model_viewer_template' => 'clone'
            ]);
    }

    /**
     * Process the clone resource request.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="create",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function clone(CrudRequest $request, CrudableModel $model)//: Response
    {
        // last time to check if something prevents the model to be cloned
        if (!$model->canBeCloned($request)) {
            throw new \RuntimeException(sprintf('Model [%s]:[%s] cannot be cloned', (new \ReflectionClass($model))->getName(), $model->getKey()));
        }

        $form = $this->getForm($request, $model);

        return $form->submit()->isValid()
            ? $this->onCloneFormValid($request, $model, $form)
            : $this->onCloneFormInvalid($request, $model, $form);
    }

    /**
     * Action to take when the 'clone' form is valid.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onCloneFormValid(CrudRequest $request, CrudableModel $model, AbstractCrudForm $form)//: Response
    {
        $clone = $this->getRepository()->cloneModel($model, $form->getFormFieldsValues());

        return $this
            ->onModelCloned($request, $clone, $form)
            ->onModelClonedSuccessResponse($request, $clone, $form);
    }

    /**
     * Action to take after the model has been cloned.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     * @return \Softworx\RocXolid\Http\Controllers\Contracts\Crudable
     */
    protected function onModelCloned(CrudRequest $request, CrudableModel $model, AbstractCrudForm $form): Crudable
    {
        return $this;
    }

    /**
     * Respond to successful model clone.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onModelClonedSuccessResponse(CrudRequest $request, CrudableModel $model, AbstractCrudForm $form)//: Response
    {
        return $this->successCloneResponse($request, $model, $form);
    }

    /**
     * Action to take when the 'clone' form was submitted with invalid data.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onCloneFormInvalid(CrudRequest $request, CrudableModel $model, AbstractCrudForm $form)//: Response
    {
        return $this->errorResponse($request, $model, $form, 'clone');
    }

    /**
     * Obtain model viewer to be used for duplicate/clone action.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param string|null $tab
     * @return \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer
     */
    protected function getCloneModelViewerComponent(CrudRequest $request, CrudableModel $model, ?string $tab = null): CrudModelViewer
    {
        $form = $this->getForm($request, $model, $tab);
        $form_component = $this->getFormComponent($form);

        return $this->getModelViewerComponent($model, $form_component);
    }







    /**
     * Clone the specified resource.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function __________clone(CrudRequest $request, CrudableModel $model)
    {
        $with_relations = $request->input('_data.with_relations', []);
        $clone_log = collect();
        $clone = $model->clone($clone_log, [], $with_relations);

        return $this->onModelCloned($request, $model, $clone);
    }

    /**
     * Action to take after the model has been cloned.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $original
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $clone
     */
    protected function ___________onModelCloned(CrudRequest $request, CrudableModel $original, CrudableModel $clone)//: Response
    {
        if ($request->ajax()) {
            return $this->response->redirect($this->getRoute('edit', $clone))->get();
        } else {
            return redirect($this->getRoute('edit', $clone));
        }
    }
}
