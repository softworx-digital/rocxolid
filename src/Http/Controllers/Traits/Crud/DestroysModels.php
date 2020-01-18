<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to remove a resource from storage.
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
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
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
     * Destroy the specified resource.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function destroy(CrudRequest $request, Crudable $model)//: Response - returns JSON for ajax calls
    {
        $this->setModel($model);

        $repository = $this->getRepository($this->getRepositoryParam($request));

        $model = $repository->deleteModel($this->getModel());

        return $this->destroyResponse($request, $model);
    }
}
