<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions;

use Softworx\RocXolid\Http\Requests\CrudRequest;

/**
 * Trait to clone a resource.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ClonesModels
{
    /**
     * Display the specified resource clone confirmation dialog.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param int $id
     */
    public function cloneConfirm(CrudRequest $request, $id)
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

        $model_viewer_component = $this->getModelViewerComponent($this->getModel());

        if ($request->ajax()) {
            return $this->response
                ->modal($model_viewer_component->fetch('modal.clone-confirm'))
                ->get();
        } else {
            return redirect($this->getRoute('edit', $this->getModel()));
        }
    }

    /**
     * Clone the specified resource.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param int $id
     */
    public function clone(CrudRequest $request, $id)
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

        $with_relations = $request->input('_data.with_relations', []);
        $clone_log = new Collection();
        $clone = $this->getModel()->clone($clone_log, [], $with_relations);

        if ($request->ajax()) {
            return $this->response->redirect($this->getRoute('edit', $clone))->get();
        } else {
            return redirect($this->getRoute('edit', $clone));
        }
    }
}
