<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions\Form;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to reload a form (upon field data change to adjust other fields' data).
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ReloadsForm
{
    /**
     * Reload Create/Update form to dynamically load related field values.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function formReload(CrudRequest $request, ?Crudable $model = null)//: Response
    {
        $model = $model ?? $this->getRepository()->getModel();
        // create form
        $form = $this->getForm($request, $model)->setFieldsRequestInput($request->input());
        // create component
        $form_component = $this->getFormComponent($form);

        return $this->response
                ->replace($form_component->getDomId('fieldset'), $form_component->fetch('include.fieldset'))
                ->get();
    }
}
