<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid repositories
use Softworx\RocXolid\Repositories\AbstractCrudRepository;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Delete (destroy) resource CRUD action.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait DestroysModels
{
    /**
     * Display the specified resource destroy confirmation dialog.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="delete",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function destroyConfirm(CrudRequest $request, Crudable $model)//: View
    {
        $this->setModel($model);

        $repository = $this->getRepository($this->getRepositoryParam($request));

        $model_viewer_component = $this->getModelViewerComponent($this->getModel());

        if ($request->ajax()) {
            return $this->response
                ->modal($model_viewer_component->fetch('modal.destroy-confirm'))
                ->get();
        } else {
            return $this
                ->getDashboard()
                ->setModelViewerComponent($model_viewer_component)
                ->render('model', [
                    'model_viewer_template' => 'destroy-confirm'
                ]);
        }
    }

    /**
     * Process the destroy resource request.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="delete",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function destroy(CrudRequest $request, Crudable $model)//: Response - returns JSON for ajax calls
    {
        $this->setModel($model);

        $repository = $this->getRepository($this->getRepositoryParam($request));

        return $this->onDestroy($request, $repository);
    }

    /**
     * Action to take when the 'destroy' form was submitted.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Repositories\AbstractCrudRepository $repository
     * @param \Softworx\RocXolid\Forms\AbstractCrudForm $form
     */
    protected function onDestroy(CrudRequest $request, AbstractCrudRepository $repository)//: Response
    {
        $model = $repository->deleteModel($this->getModel());

        return $this->onModelDestroyed($request, $repository, $model);
    }

    /**
     * Action to take after the model is destroyed.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Repositories\AbstractCrudRepository $repository
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    protected function onModelDestroyed(CrudRequest $request, AbstractCrudRepository $repository, Crudable $model)//: Response
    {
        return $this->destroyResponse($request, $model);
    }
}
