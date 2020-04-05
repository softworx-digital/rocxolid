<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions;

use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

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
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function cloneConfirm(CrudRequest $request, Crudable $model)
    {
        $model_viewer_component = $this->getModelViewerComponent($model);

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
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function clone(CrudRequest $request, Crudable $model)
    {
        $with_relations = $request->input('_data.with_relations', []);
        $clone_log = collect();
        $clone = $model->clone($clone_log, [], $with_relations);

        return $this->onModelCloned($request, $model, $clone);
    }

    /**
     * Action to take after the model has been cloned.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $original
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $clone
     */
    protected function onModelCloned(CrudRequest $request, Crudable $original, Crudable $clone)//: Response
    {
        if ($request->ajax()) {
            return $this->response->redirect($this->getRoute('edit', $clone))->get();
        } else {
            return redirect($this->getRoute('edit', $clone));
        }
    }
}
