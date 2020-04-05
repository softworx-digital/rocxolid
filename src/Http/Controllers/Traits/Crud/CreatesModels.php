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
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     */
    public function create(CrudRequest $request)//: View
    {
        $model_viewer_component = $this->getModelViewerComponent(
            $this->getRepository()->getModel(),
            $this->getFormComponent($this->getForm($request))
        );

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
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     */
    public function store(CrudRequest $request)//: Response
    {
        $form = $this
            ->getForm($request)
            ->submit();

        if ($form->isValid()) {
            return $this->onStore($request, $form);
        } else {
            return $this->onStoreError($request, $form);
        }
    }

    /**
     * Action to take when the 'create' form was validated.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onStore(CrudRequest $request, AbstractCrudForm $form)//: Response
    {
        $model = $this->getRepository()->createModel($form->getFormFieldsValues(), 'create');

        return $this->onModelStored($request, $model, $form);
    }

    /**
     * Action to take after the model has been created and saved.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onModelStored(CrudRequest $request, Crudable $model, AbstractCrudForm $form)//: Response
    {
        return $this->successResponse($request, $model, $form, 'create');
    }

    /**
     * Action to take when the 'create' form was submitted with invalid data.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onStoreError(CrudRequest $request, AbstractCrudForm $form)//: Response
    {
        return $this->errorResponse($request, $form, 'create');
    }
}
