<?php

namespace Softworx\RocXolid\Http\Controllers;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;
// rocXolid forms
use Softworx\RocXolid\Forms\AbstractCrudForm;
// rocXolid controllers
use Softworx\RocXolid\Http\Controllers\AbstractCrudController;

/**
 * Utility rocXolid controller for CRUD (and associated) operations associated to sections.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo: incorporate properly
 */
abstract class AbstractCrudControllerWithSectionResponse extends AbstractCrudController
{
    /**
     * {@inheritDoc}
     */
    protected function successAjaxStoreResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form)
    {
        $model_viewer_component = $model->getModelViewerComponent();

        return $this->response
            ->modalClose($model_viewer_component->getDomId(sprintf('modal-%s', $form->getParam())))
            ->notifySuccess($model_viewer_component->translate('text.created'))
            ->redirect($this->getRoute('show', $model))
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    protected function successNonAjaxStoreResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form)
    {
        return redirect($this->getRoute('show', $model));
    }

    /**
     * {@inheritDoc}
     */
    protected function successAjaxResponse(CrudRequest $request, Crudable $model, AbstractCrudForm $form): array
    {
        if (!$request->has('_section')) {
            throw new \RuntimeException('Request is missing [_section] param'); // @todo: put in request validation
        }

        $model_viewer_component = $model->getModelViewerComponent();

        $template_name = sprintf('include.%s', $request->_section);

        return $this->response
            ->replace($model_viewer_component->getDomId($request->_section), $model_viewer_component->fetch($template_name))
            ->replace($model_viewer_component->getDomId('actions'), $model_viewer_component->fetch('include.actions'))
            ->modalClose($model_viewer_component->getDomId(sprintf('modal-%s', $form->getParam())))
            ->notifySuccess($model_viewer_component->translate('text.updated'))
            ->get();
    }
}
