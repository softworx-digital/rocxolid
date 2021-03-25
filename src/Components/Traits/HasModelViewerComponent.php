<?php

namespace Softworx\RocXolid\Components\Traits;

// rocXolid component contracts
use Softworx\RocXolid\Components\Contracts\Componentable\ModelViewer as ModelViewerComponentable;
// rocXolid components
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer;

/**
 * Enables object to have a model viewer component assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait HasModelViewerComponent
{
    /**
     * Model viewer component reference.
     *
     * @var \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer
     */
    protected $model_viewer_component;

    /**
     * {@inheritDoc}
     */
    public function setModelViewerComponent(CrudModelViewer $model_viewer_component): ModelViewerComponentable
    {
        $this->model_viewer_component = $model_viewer_component;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getModelViewerComponent(): CrudModelViewer
    {
        if (!isset($this->model_viewer_component)) {
            throw new \RuntimeException(sprintf('CRUD model_viewer_component not yet set to [%s]', get_class($this)));
        }

        return $this->model_viewer_component;
    }

    /**
     * {@inheritDoc}
     */
    public function hasModelViewerComponent(): bool
    {
        return !isset($this->model_viewer_component);
    }
}
