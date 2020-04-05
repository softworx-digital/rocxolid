<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions\Form;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;
// rocXolid form components
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;
use Softworx\RocXolid\Components\Forms\FormFieldGroup as FormFieldGroupComponent;

/**
 * Trait to reload a form group (upon field data change to adjust other fields' data).
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ReloadsFormGroup
{
    /**
     * Reload Create/Update form to dynamically load related field values and return given form field group.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param string $field_group
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function formReloadGroup(CrudRequest $request, string $field_group, ?Crudable $model = null)//: Response
    {
        $model = $model ?? $this->getRepository()->getModel();
        // create form with this group
        $form = $this->getForm($request, $model)->setFieldsRequestInput($request->input());
        // this is needed for composing the fields
        $form_component = $this->getFormComponent($form); // @todo: make this not needed...?
        // this is needed for DOM ID
        $form_field_group_component = FormFieldGroupComponent::build($this, $this)
            ->setFormFieldGroup($form->getFormFieldGroup($field_group));

        $this->response->replace(
            $form_field_group_component->getDomId($field_group),
            $form_component->fetch('include.fieldset-only-group', [ 'group' => $field_group, 'show' => !$request->has('hide') ])
        );

        return $this->response->get();
    }
}
