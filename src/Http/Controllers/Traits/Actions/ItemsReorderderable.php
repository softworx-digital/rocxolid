<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to enable reordering of objects.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ItemsReorderderable
{
    /**
     * Process incoming request.
     * Retrieve the object order and reset the position column.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param string $relation
     */
    public function reorder(CrudRequest $request, Crudable $model, string $relation)//: View
    {
        if (($input = $request->input('_data', false)) && is_array($input) && ($input = reset($input))) {
            $order = [];
            foreach ($input as $position => $item_data) {
                $order[$item_data['itemId']] = $position;
            }
        } else {
            throw \InvalidArgumentException(sprintf('Invalid data for reordering provided'));
        }

        foreach ($model->$relation as $item) {
            if (isset($order[$item->getKey()]) && isset($item->{$item::POSITION_COLUMN})) {
                $item->{$item::POSITION_COLUMN} = $order[$item->getKey()];
                $item->save();
            }
        }

        $model_viewer_component = $this->getModelViewerComponent($model);

        return $this->response
            ->notifySuccess($model_viewer_component->translate('text.updated'))
            ->get();
    }
}
