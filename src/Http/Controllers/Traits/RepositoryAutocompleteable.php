<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

use Softworx\RocXolid\Http\Requests\CrudRequest;
use Softworx\RocXolid\Filters\StartsWith;

/**
 *
 */
trait RepositoryAutocompleteable
{
    public function repositoryAutocomplete(CrudRequest $request, $id = null)//: View
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));
        $model = $id ? $repository->find($id) : $repository->getModel();
        $model->setQueryString($request->get('q', null));

        $this->setModel($model);

        if ($request->has('form-param')) {
            $form_param = $request->get('form-param');
        } else {
            $form_param = $model->exists ? 'update' : 'create';
        }

        $field = $repository
                ->getForm($form_param)
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
