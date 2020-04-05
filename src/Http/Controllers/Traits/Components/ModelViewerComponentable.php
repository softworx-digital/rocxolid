<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Components;

// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid components
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer as CrudModelViewerComponent;
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;

/**
 * Helper trait to obtain model viewer component.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ModelViewerComponentable
{
    protected static $model_viewer_type = CrudModelViewerComponent::class;

    /**
     * Retrieve model viewer component to show.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Softworx\RocXolid\Components\Forms\CrudForm|null $form_component
     * @return \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer
     */
    public function getModelViewerComponent(CrudableModel $model, ?CrudFormComponent $form_component = null): CrudModelViewerComponent
    {
        $model_viewer_component = static::$model_viewer_type::build($this, $this)
            ->setModel($model)
            ->setController($this);

        if ($form_component) {
            $model_viewer_component->setFormComponent($form_component);
        }

        return $model_viewer_component;
    }
}
