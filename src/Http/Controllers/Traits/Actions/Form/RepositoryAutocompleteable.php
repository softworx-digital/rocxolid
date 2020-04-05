<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions\Form;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid filters
use Softworx\RocXolid\Filters\StartsWith;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;
// rocXolid model scopes
use Softworx\RocXolid\Models\Scopes\Owned as OwnedScope;

/**
 * Trait to enable autocompletion feature for form fields.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo: totally refactor this feature
 */
trait RepositoryAutocompleteable
{
    /**
     * Process the incoming autocomplete-field request.
     * Retrieve the field upon request param, set appropriate filter to the field's collection.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @todo: use scopes rather than filter
     * @todo: parameter naming convention ('f' is ugly)
     */
    public function repositoryAutocomplete(CrudRequest $request, ?Crudable $model = null)//: View
    {
        $model = $model ?? $this->getRepository()->getModel();
        // @todo: the repository calls the controller to get the model, which can be different from
        // what is needed (eg. user registration controller vs. city_id)
        $model->setQueryString($request->get('q', null));
        // create form
        $form = $this->getForm($request, $model)->setFieldsRequestInput($request->input());
        // set field values filter based on query param
        $field = $form->getFormField($request->get('f', null))
            ->addFilter([
                'class' => StartsWith::class,
                'data' => $model
            ]);
        // @todo: use collection
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
