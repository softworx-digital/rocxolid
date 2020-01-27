<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid form components
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to update a resource.
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
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function edit(CrudRequest $request, Crudable $model)//: View
    {
        $this->setModel($model);

        $repository = $this->getRepository($this->getRepositoryParam($request));

        $form = $repository->getForm($this->getFormParam($request));
        $form
            ->adjustUpdate($request);

        $form_component = CrudFormComponent::build($this, $this)
            ->setForm($form)
            ->setRepository($repository);

        $model_viewer_component = $this
            ->getModelViewerComponent($this->getModel())
            ->setFormComponent($form_component)
            ->adjustUpdate($request, $this);

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
     * Update the edited model.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="update",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function update(CrudRequest $request, Crudable $model)//: Response
    {
        $this->setModel($model);

        $repository = $this->getRepository($this->getRepositoryParam($request));

        $form = $repository->getForm($this->getFormParam($request));
        $form
            //->adjustUpdate($request)
            ->adjustUpdateBeforeSubmit($request)
            ->submit();

        if ($form->isValid()) {
            return $this->success($request, $repository, $form, 'update');
        } else {
            return $this->errorResponse($request, $repository, $form, 'update');
        }
    }
}
