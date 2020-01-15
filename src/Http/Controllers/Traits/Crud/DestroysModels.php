<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud;

use Softworx\RocXolid\Http\Requests\CrudRequest;

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
     * @param int $id
     */
    public function destroyConfirm(CrudRequest $request, $id)//: View
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

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
     * @param int $id
     */
    public function destroy(CrudRequest $request, $id)//: Response - returns JSON for ajax calls
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

        $model = $repository->deleteModel($this->getModel());

        return $this->destroyResponse($request, $model);
    }
}
