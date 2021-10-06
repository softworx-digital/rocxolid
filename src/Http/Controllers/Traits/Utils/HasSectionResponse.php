<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Utils;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;
// rocXolid forms
use Softworx\RocXolid\Forms\AbstractCrudForm;

/**
 * Utility trait for CRUD (and associated) operations responses aligned to sections.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait HasSectionResponse
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
            throw new \RuntimeException('Request is missing [_section] param'); // @todo put in request validation
        }

        $model_viewer_component = $model->getModelViewerComponent();

        $dom_id_param = $this->getSectionDomIdParam($request, $model, $form);
        $template_name = $this->getSectionTemplateName($request, $model, $form);
        $assignments = $this->getSectionTemplateAssignments($request, $model, $form);

        return $this->response
            ->replace($model_viewer_component->getDomId($dom_id_param), $model_viewer_component->fetch($template_name, $assignments))
            ->replace($model_viewer_component->getDomId('actions'), $model_viewer_component->fetch('include.actions'))
            ->modalClose($model_viewer_component->getDomId(sprintf('modal-%s', $form->getParam())))
            ->notifySuccess($model_viewer_component->translate('text.updated'))
            ->get();
    }

    protected function getSectionTemplateAssignments(CrudRequest $request, Crudable $model, AbstractCrudForm $form): array
    {
        [ $section, $param ] = explode('.', $request->_section, 2);

        return [
            'param' => $param,
        ];
    }

    protected function getSectionDomIdParam(CrudRequest $request, Crudable $model, AbstractCrudForm $form): string
    {
        [ $section, $param ] = explode('.', $request->_section, 2);
        [ $section, $template ] = explode(':', sprintf('%s:default', $section));

        return sprintf('%s.%s', $section, $param);
    }

    protected function getSectionTemplateName(CrudRequest $request, Crudable $model, AbstractCrudForm $form): string
    {
        [ $section, $param ] = explode('.', $request->_section, 2);
        [ $section, $template ] = explode(':', sprintf('%s:default', $section));

        return sprintf('%s.%s', $section, $template);
    }
}
