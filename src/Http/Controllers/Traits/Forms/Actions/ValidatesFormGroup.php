<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Forms\Actions;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;
// rocXolid form components
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;
use Softworx\RocXolid\Components\Forms\FormFieldGroup as FormFieldGroupComponent;

/**
 * Trait to validate a form group.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ValidatesFormGroup
{
    /**
     * Validate group of Create/Update form fields.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param string $field_group
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function formValidateGroup(CrudRequest $request, string $field_group, ?Crudable $model = null)//: Response
    {
        $model = $model ?? $this->getRepository()->getModel();
        // create form with this group
        $form = $this->getForm($request, $model)->setFieldsRequestInput($request->input());
        // submit form with group fields only
        $form->submitGroup($field_group);
        // this is needed for composing the fields
        $form_component = $this->getFormComponent($form); // @todo make this not needed...?
        // this is needed for DOM ID
        $form_field_group_component = FormFieldGroupComponent::build($this, $this)
            ->setFormFieldGroup($form->getFormFieldGroup($field_group));

        $form->getFieldGroups()->each(function (string $field_group) use ($form_field_group_component, $form_component) {
            $this->response->replace(
                $form_field_group_component->getDomId($field_group),
                $form_component->fetch('include.fieldset-only-group', [ 'group' => $field_group ])
            );
        });

        if (!$form->isValid()) {
            $this->response->errors(collect($form->getErrors()->all()));
        }

        $form->addToResponse($this->response);

        return $this->response->get();
    }
}
