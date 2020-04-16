<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud\Response;

// use Symfony\Component\HttpFoundation\Response;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid repositories
// use Softworx\RocXolid\Repositories\AbstractCrudRepository;
use Softworx\RocXolid\Repositories\Contracts\Repository;
// rocXolid forms
use Softworx\RocXolid\Forms\AbstractCrudForm;
// rocXolid form components
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to provide error response to a CRUD request.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ProvidesErrorResponse
{
    // @todo: refactor to ease overrideability & add model reference if possible
    // @todo: seprate ajax / non-ajax like success response
    protected function errorResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form, string $action)
    {
        $form_component = $this->getFormComponent($form);

        if ($request->ajax()) {
            // @todo: refactor - some validation, etc...
            if ($request->has('_form_field_group')) {
                $form_field_group_component = $form_component->getFormFieldGroupsComponents()->get($request->input('_form_field_group'));

                return $this->response
                    ->notifyError($form_component->translate('text.form-error'))
                    ->replace(
                        $form_field_group_component->getDomId($form_field_group_component->getFormFieldGroup()->getName()),
                        $form_field_group_component->fetch($form_field_group_component->getOption('template', $form_field_group_component->getDefaultTemplateName()), ['show' => true])
                    )
                    ->get();
            } else {
                return $this->response
                    ->notifyError($form_component->translate('text.form-error'))
                    ->replace($form_component->getDomId('fieldset'), $form_component->fetch('include.fieldset'))
                    ->get();
            }
        } else {
            // @todo: "hotfixed", you can do better
            if ($action == 'update') {
                $action = 'edit';
            }

            $route_params = $request->filled('_section') ? [ '_section' => $request->get('_section') ] : [];

            $route = $model->exists
                ? $this->getRoute($action, $model, $route_params)
                : $this->getRoute($action, $route_params);

            return redirect($route)
                ->with($form->getSessionParam('errors'), $form->getErrors())
                ->with($form->getSessionParam('input'), $request->input());
        }
    }
}
