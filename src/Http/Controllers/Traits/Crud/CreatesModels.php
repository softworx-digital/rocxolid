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
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer as CrudModelViewerComponent;

/**
 * Trait to create a resource.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait CreatesModels
{
    /**
     * Display the specified resource create form.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="create",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     */
    public function create(CrudRequest $request)//: View
    {
        $model_viewer_component = $this->getModelViewerComponent(
            $this->getRepository()->getModel(),
            $this->getFormComponent($this->getForm($request))
        );

        return $request->ajax()
            ? $this->createAjax($request, $model_viewer_component)
            : $this->createNonAjax($request, $model_viewer_component);
    }

    /**
     * Display the specified resource create form modal for AJAX requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer $model_viewer_component
     */
    protected function createAjax(CrudRequest $request, CrudModelViewerComponent $model_viewer_component)//: View
    {
        return $this->response
            ->modal($model_viewer_component->fetch('modal.create'))
            ->get();
    }

    /**
     * Display the specified resource create form view for non-AJAX requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer $model_viewer_component
     */
    protected function createNonAjax(CrudRequest $request, CrudModelViewerComponent $model_viewer_component)//: View
    {
        return $this
            ->getDashboard()
            ->setModelViewerComponent($model_viewer_component)
            ->render('model', [
                'model_viewer_template' => 'create'
            ]);
    }

    /**
     * Process the store resource request.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="create",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     */
    public function store(CrudRequest $request)//: Response
    {
        // last time to check if something prevents the model to be created
        if (!$this->getRepository()->getModel()->canBeCreated($request)) {
            throw new \RuntimeException(sprintf('Model [%s] cannot be created', (new \ReflectionClass($this->getRepository()->getModel()))->getName()));
        }

        $form = $this->getForm($request);

        return $form->submit()->isValid()
            ? $this->onStoreFormValid($request, $form)
            : $this->onStoreFormInvalid($request, $form);
    }

    /**
     * Action to take when the 'create' form is valid.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onStoreFormValid(CrudRequest $request, AbstractCrudForm $form)//: Response
    {
        $model = $this->getRepository()->createModel($form->getFormFieldsValues());

        return $this
            ->onModelStored($request, $model, $form)
            ->onModelStoredSuccessResponse($request, $model, $form);
    }

    /**
     * Action to take after the model has been created and saved.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     * @return \Softworx\RocXolid\Http\Controllers\Contracts\Crudable
     */
    protected function onModelStored(CrudRequest $request, CrudableModel $model, AbstractCrudForm $form): Crudable
    {
        return $this;
    }

    /**
     * Respond to successful model creation.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onModelStoredSuccessResponse(CrudRequest $request, CrudableModel $model, AbstractCrudForm $form)//: Response
    {
        return $this->successStoreResponse($request, $model, $form, 'create');
    }

    /**
     * Action to take when the 'create' form was submitted with invalid data.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onStoreFormInvalid(CrudRequest $request, AbstractCrudForm $form)//: Response
    {
        return $this->errorResponse($request, $this->getRepository()->getModel(), $form, 'create');
    }
}
