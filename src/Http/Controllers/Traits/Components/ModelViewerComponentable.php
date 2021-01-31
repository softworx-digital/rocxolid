<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Components;

// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;
// rocXolid components
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer;
use Softworx\RocXolid\Components\Forms\CrudForm;

/**
 * Helper trait to obtain model viewer component.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ModelViewerComponentable
{
    /**
     * Model viewer type definition.
     *
     * @var string
     */
    protected static $model_viewer_type = CrudModelViewer::class;

    /**
     * Retrieve model viewer component to show.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable|null $model
     * @param \Softworx\RocXolid\Components\Forms\CrudForm|null $form_component
     * @return \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer
     */
    public function getModelViewerComponent(?Crudable $model = null, ?CrudForm $form_component = null): CrudModelViewer
    {
        $model = $model ?? $this->getRepository()->getModel();
        $this->initModel($model);

        $model_viewer_component = static::$model_viewer_type::build($this, $this)
            ->setModel($model)
            ->setController($this);

        $model->setModelViewerComponentProperties($model_viewer_component);

        if ($form_component) {
            $model_viewer_component->setFormComponent($form_component);
        }

        return $model_viewer_component;
    }
}
