<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid controller contracts
use Softworx\RocXolid\Http\Controllers\Contracts\Crudable;
// rocXolid repositories
use Softworx\RocXolid\Repositories\AbstractCrudRepository;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid components
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer as CrudModelViewerComponent;

/**
 * Delete (destroy) resource CRUD action.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait DestroysModels
{
    use Response\ProvidesDestroyResponse;

    /**
     * Flag if to process the destroy confirmation AJAX-ish or standard (sync) HTTP Request-ish way.
     *
     * @var boolean
     */
    protected $use_ajax_destroy_confirmation = false;

    /**
     * Display the specified resource update form.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="update",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function destroyConfirm(CrudRequest $request, CrudableModel $model)//: View
    {
        // $model_viewer_component = $this->getModelViewerComponent($model, $this->getFormComponent($this->getForm($request, $model)));
        $model_viewer_component = $this->getModelViewerComponent($model);

        return $request->ajax()
            ? $this->destroyConfirmAjax($request, $model, $model_viewer_component)
            : $this->destroyConfirmNonAjax($request, $model, $model_viewer_component);
    }

    /**
     * Display the specified resource destroy confirmation form modal for AJAX requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer $model_viewer_component
     */
    protected function destroyConfirmAjax(CrudRequest $request, CrudableModel $model, CrudModelViewerComponent $model_viewer_component)
    {
        return $this->response
            ->modal($model_viewer_component->fetch('modal.destroy-confirm'))
            ->get();
    }

    /**
     * Display the specified resource destroy confirmation form view for non-AJAX requests.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer $model_viewer_component
     */
    protected function destroyConfirmNonAjax(CrudRequest $request, CrudableModel $model, CrudModelViewerComponent $model_viewer_component)
    {
        return $this
            ->getDashboard()
            ->setModelViewerComponent($model_viewer_component)
            ->render('model', [
                'model_viewer_template' => 'destroy-confirm'
            ]);
    }

    /**
     * Process the destroy resource request.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="delete",scopes="['policy.scope.all','policy.scope.owned']")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function destroy(CrudRequest $request, CrudableModel $model)//: Response - returns JSON for ajax calls
    {
        // last time to check if something prevents the model to be destroyed
        if (!$model->canBeDeleted($request)) {
            throw new \RuntimeException(sprintf('Model [%s]:[%s] cannot be deleted', (new \ReflectionClass($model))->getName(), $model->getKey()));
        }

        return $this->onDestroy($request, $model);
    }

    /**
     * Action to take when the 'destroy' form was submitted.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    protected function onDestroy(CrudRequest $request, CrudableModel $model)//: Response
    {
        $model = $this->getRepository()->deleteModel($model);

        return $this
            ->onModelDestroyed($request, $model)
            ->onModelDestroyedSuccessResponse($request, $model);
    }

    /**
     * Action to take after the model is destroyed.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @return \Softworx\RocXolid\Http\Controllers\Contracts\Crudable
     */
    protected function onModelDestroyed(CrudRequest $request, CrudableModel $model): Crudable
    {
        return $this;
    }

    /**
     * Respond to successful model destruction.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    protected function onModelDestroyedSuccessResponse(CrudRequest $request, CrudableModel $model)//: Response
    {
        return $this->destroyResponse($request, $model);
    }

    /**
     * Decide whether to process the destroy confirmation AJAX-ish or standard (sync) HTTP Request-ish way.
     *
     * @return bool
     */
    public function useAjaxDestroyConfirmation(): bool
    {
        return $this->use_ajax_destroy_confirmation;
    }
}
