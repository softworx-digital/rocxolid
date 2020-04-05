<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to switch 'is_enabled' model property.
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
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @throws \RuntimeException If is_enabled property not fillable.
     */
    public function switchEnability(CrudRequest $request, Crudable $model)
    {
        $this->authorize('update', $model);

        if (!$model->isFillable('is_enabled')) {
            throw new \RuntimeException(sprintf('Attribute [%s] is not fillable for [%s]:[%s]', 'is_enabled', get_class($model), $model->getKey()));
        }

        $model->fill([
            'is_enabled' => !$model->is_enabled,
        ])->save();

        $table_component = $this->getTableComponent($this->getTable($request));

        if ($request->ajax()) {
            return $this->response
                ->replace($table_component->getDomId('row', $model->getKey()), $table_component->fetch('include.results-row', [ 'model' => $model ]))
                ->get();
        } else {
            return redirect($this->getRoute('index'));
        }
    }
}
