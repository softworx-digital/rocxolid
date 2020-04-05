<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud\Response;

// use Symfony\Component\HttpFoundation\Response;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid forms
use Softworx\RocXolid\Forms\AbstractCrudForm;
// rocXolid form components
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to provide success response to a CRUD request.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ProvidesSuccessResponse
{
    // @todo: refactor to ease overrideability
    protected function successResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form, string $action)
    {
        $assignments = [];

        // @todo: use constants rather than strings
        // @todo: divide into <action> => <method> and wrap into property or class
        if ($request->ajax()) {
            switch ($request->input('_submit-action')) {
                case 'submit-new':
                    $this->response->redirect($this->getRoute('create'));
                break;
                case 'submit-edit':
                    switch ($action) {
                        case 'create':
                            $this->response->redirect($this->getRoute('edit', $model));
                        break;
                        case 'update':
                            $this->response->replace($form_component->getDomId(), $form_component->fetch('update'));
                        break;
                    }
                break;
                case 'submit-show':
                    $this->response->redirect($this->getRoute('show', $model));
                break;
                default:
                    $this->response->redirect($this->getRoute('index'));
            }

            return $this->response
                ->notifySuccess($model->getModelViewerComponent()->translate('text.updated'))
                //->append($form_component->getDomId('output'), Message::build($this, $this)->fetch('crud.success', $assignments))
                ->get();
        } else {
            switch ($request->input('_submit-action')) {
                case 'submit-new':
                    $route = $this->getRoute('create');
                break;
                case 'submit-edit':
                    $route = $this->getRoute('edit', $model);
                break;
                case 'submit-show':
                    $route = $this->getRoute('show', $model);
                break;
                default:
                    $route = $this->getRoute('index');
            }

            return redirect($route)
                ->with($form->getSessionParam('errors'), $form->getErrors())
                ->with($form->getSessionParam('input'), $request->input());
        }
    }
}
