<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

// @todo upratat
use Softworx\RocXolid\Http\Requests\CrudRequest;
use Softworx\RocXolid\Filters\StartsWith;

/**
 *
 */
trait RepositoryTypeaheadable
{
    public function repositoryTypeahead(CrudRequest $request, $id = null)//: View
    {
        dd(__METHOD__);
        $repository = $this->getRepository($this->getRepositoryParam($request));
        $model = $id ? $repository->find($id) : $repository->getModel();
        $model->setQueryString($request->get('q', null));

        $this->setModel($model);

        $field = $repository
                ->getForm($model->exists ? 'update' : 'create')
                ->getFormField($request->get('f', null))
                    ->addFilter([
                        'class' => StartsWith::class,
                        'data' => $model
                    ]);

        $response = [];

        foreach ($field->getCollection() as $value => $text) {
            $response[] = [
                'value' => $value,
                'text' => $text,
            ];
        }

        return response()->json($response);
    }
}
