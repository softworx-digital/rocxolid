<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
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
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param string $group
     * @param mixed $id
     * @todo: verify if $int can be type hinted as int
     */
    public function formReloadGroup(CrudRequest $request, string $group, $id = null)//: Response
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $model = $id ? $repository->findOrFail($id) : $repository->getModel();

        $this->setModel($model);

        // @todo: refactor to clearly identify the form we want to get, not artificially like this
        // put form->options['route-action'], or full identification data into the request
        // this can serve as a fallback
        if ($model->exists) {
            $form = $repository
                ->getForm($this->getFormParam($request, 'update'))
                    ->setFieldsRequestInput($request->input());
        } else {
            $form = $repository
                ->getForm($this->getFormParam($request, 'create'))
                    ->setFieldsRequestInput($request->input());
        }

        $form_field_group = $form->getFormFieldGroup($group);

        // this is needed for composing the fields
        $form_component = CrudFormComponent::build($this, $this)
            ->setForm($form)
            ->setRepository($repository);

        // this is needed for DOM id
        $form_field_group_component = FormFieldGroupComponent::build($this, $this)
            ->setFormFieldGroup($form_field_group);

        $this->response->replace(
            $form_field_group_component->getDomId($group),
            $form_component->fetch('include.fieldset-only-group', ['group' => $group])
        );

        return $this->response->get();
    }
}
