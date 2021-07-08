<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to enable reordering of child objects.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo refactor, maybe delegate to a service + add interface to models
 */
trait ReordersModels
{
    /**
     * Process incoming request.
     * Retrieve the object order and reset the position column.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param string $relation
     */
    public function reorder(CrudRequest $request, Crudable $model, string $relation, ?string $position_column = null)//: View
    {
        if (($input = $request->input('_data', false)) && is_array($input) && ($input = reset($input))) {
            $order = [];
            foreach ($input as $position => $item_data) {
                $order[$item_data['itemId']] = $position;
            }
        } else {
            throw \InvalidArgumentException(sprintf('Invalid data for reordering provided'));
        }

        $model->$relation->each(function (Crudable $item) use ($order, $position_column) {
            $position_column = $position_column ?? $item::POSITION_COLUMN; // @todo fn to get positining column

            if (isset($order[$item->getKey()]) && isset($item->$position_column)) {
                $item->$position_column = $order[$item->getKey()];
                $item->save();
            }
        });

        $model_viewer_component = $this->getModelViewerComponent($model);

        return $this->response
            ->notifySuccess($model_viewer_component->translate('text.updated'))
            ->get();
    }
}
