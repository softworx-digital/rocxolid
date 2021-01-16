<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Forms\Actions;

use Illuminate\Http\JsonResponse;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Searchable;

/**
 * Trait to enable autocompletion feature for form fields.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait AutocompletesFormField
{
    /**
     * Process the incoming form field autocomplete request.
     * Retrieve the form and form field upon request params, obtain results from field's autocompletion.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param string $param Controller's form param.
     * @param string $field Form's field name.
     * @return \Illuminate\Http\JsonResponse
     */
    public function formFieldAutocomplete(CrudRequest $request, string $param, string $field): JsonResponse
    {
        // set the 'route-action' option to null in order to avoid form action creation
        $form = $this->makeForm($request, null, $param, [ 'route-action' => null ]);
        $field = $form->getFormField($field);
        $data = $field->autocomplete($request->input('q'));

        return response()->json($data->transform(function (Searchable $model) {
            return $model->toSearchResult();
        }));
    }
}
