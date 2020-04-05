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
 * Update resource CRUD action.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait UpdatesModels
{
    /**
     * Display the specified resource update form.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="update",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function edit(CrudRequest $request, Crudable $model)//: View
    {
        $model_viewer_component = $this->getModelViewerComponent(
            $this->getRepository()->getModel(),
            $this->getFormComponent($this->getForm($request))
        );

        if ($request->ajax()) {
            return $this->response
                ->modal($model_viewer_component->fetch('modal.update'))
                ->get();
        } else {
            return $this
                ->getDashboard()
                ->setModelViewerComponent($model_viewer_component)
                ->render('model', [
                    'model_viewer_template' => 'update'
                ]);
        }
    }

    /**
     * Process the update resource request.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="update",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function update(CrudRequest $request, Crudable $model)//: Response
    {
        $form = $this
            ->getForm($request)
            ->submit();

        if ($form->isValid()) {
            return $this->onUpdate($request, $model, $form);
        } else {
            return $this->onUpdateError($request, $model, $form);
        }
    }

    /**
     * Action to take when the 'update' form was validated.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onUpdate(CrudRequest $request, Crudable $model, AbstractCrudForm $form)//: Response
    {
        $model = $this->getRepository()->updateModel($model, $form->getFormFieldsValues()->toArray(), 'update');

        return $this->onModelUpdated($request, $model, $form);
    }

    /**
     * Action to take after the model has been updated and saved.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onModelUpdated(CrudRequest $request, Crudable $model, AbstractCrudForm $form)//: Response
    {
        return $this->successResponse($request, $model, $form, 'update');
    }

    /**
     * Action to take when the 'update' form was submitted with invalid data.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onUpdateError(CrudRequest $request, Crudable $model, AbstractCrudForm $form)//: Response
    {
        return $this->errorResponse($request, $model, $form, 'update');
    }
}
