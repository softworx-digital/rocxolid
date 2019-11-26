<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

use Softworx\RocXolid\Http\Requests\CrudRequest;
use Softworx\RocXolid\Components\General\Message;

/**
 *
 */
trait ItemsReorderderable
{
    public function reorder(CrudRequest $request, $id, $relation)//: View
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));
        $model = $id ? $repository->find($id) : $repository->getModel();

        if (($input = $request->input('_data', false)) && is_array($input) && ($input = reset($input))) {
            $order = [];
            foreach ($input as $position => $item_data) {
                $order[$item_data['itemId']] = $position;
            }
        } else {
            throw \InvalidArgumentException(sprintf('Invalid data for reordering provided'));
        }

        foreach ($model->$relation as $item) {
            if (isset($order[$item->id]) && isset($item->{$item::POSITION_COLUMN})) {
                $item->{$item::POSITION_COLUMN} = $order[$item->id];
                $item->save();
            }
        }

        $model_viewer_component = $this->getModelViewerComponent($model);

        return $this->response
            ->append($model_viewer_component->getDomId('output-icon'), (new Message())->fetch('input-feedback.success'))
            ->get();
    }
}
