<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions;

// rocXolid utils
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
trait SwitchesEnability
{
    /**
     * Clone the specified resource.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param int $id
     */
    public function switchEnability(CrudRequest $request, Crudable $model)
    {
        $this->authorize('update', $model);

        $this->setModel($model);

        $model->fill([
            'is_enabled' => !$model->is_enabled,
        ])->save();

        $repository = $this->getRepository($this->getRepositoryParam($request));
        $table_component = $this->getTableComponent($repository);

        if ($request->ajax()) {
            return $this->response
                ->replace($table_component->getDomId('row', $model->getKey()), $table_component->fetch('include.results-row', [ 'model' => $model ]))
                ->get();
        } else {
            return redirect($this->getRoute('index'));
        }
    }
}
