<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid repositories
use Softworx\RocXolid\Repositories\AbstractCrudRepository;
// rocXolid forms
use Softworx\RocXolid\Forms\AbstractCrudForm;
// rocXolid form components
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

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
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     */
    public function create(CrudRequest $request)//: View
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->getModel());

        $form = $repository->getForm($this->getFormParam($request));
        $form
            ->adjustCreate($request);

        $form_component = CrudFormComponent::build($this, $this)
            ->setForm($form)
            ->setRepository($repository);

        $model_viewer_component = $this
            ->getModelViewerComponent($this->getModel())
            ->setFormComponent($form_component)
            ->adjustCreate($request, $this);

        if ($request->ajax()) {
            return $this->response
                ->modal($model_viewer_component->fetch('modal.create'))
                ->get();
        } else {
            return $this
                ->getDashboard()
                ->setModelViewerComponent($model_viewer_component)
                ->render('model', [
                    'model_viewer_template' => 'create'
                ]);
        }
    }

    /**
     * Process the store resource request.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="create",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     */
    public function store(CrudRequest $request)//: Response
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        // @todo: ugly having it this way
        $this->setModel($repository->getModel());

        $form = $repository->getForm($this->getFormParam($request));
        $form
            //->adjustCreate($request)
            ->adjustCreateBeforeSubmit($request)
            ->submit();

        if ($form->isValid()) {
            return $this->onStore($request, $repository, $form);
        } else {
            return $this->onStoreError($request, $repository, $form);
        }
    }

    /**
     * Action to take when the 'create' form was validated.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Repositories\AbstractCrudRepository $repository
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onStore(CrudRequest $request, AbstractCrudRepository $repository, AbstractCrudForm $form)//: Response
    {
        $model = $repository->updateModel($form->getFormFieldsValues()->toArray(), $this->getModel(), 'create');

        return $this->onModelStored($request, $repository, $form, $model);
    }

    /**
     * Action to take after the model has been created and saved.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Repositories\AbstractCrudRepository $repository
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    protected function onModelStored(CrudRequest $request, AbstractCrudRepository $repository, AbstractCrudForm $form, Crudable $model)//: Response
    {
        return $this->successResponse($request, $repository, $form, $model, 'create');
    }

    /**
     * Action to take when the 'create' form was submitted with invalid data.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Repositories\AbstractCrudRepository $repository
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onStoreError(CrudRequest $request, AbstractCrudRepository $repository, AbstractCrudForm $form)//: Response
    {
        return $this->errorResponse($request, $repository, $form, 'create');
    }
}
