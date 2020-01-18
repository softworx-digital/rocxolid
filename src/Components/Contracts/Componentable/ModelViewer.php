<?php

namespace Softworx\RocXolid\Components\Contracts\Componentable;

use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer;

/**
 * Allow model viewer component to be added.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface ModelViewer
{
    /**
     * Set model viewer component.
     *
     * @param \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer $component
     * @return \Softworx\RocXolid\Components\Contracts\Componentable\ModelViewer
     */
    public function setModelViewerComponent(CrudModelViewer $component): ModelViewer;

    /**
     * Get model viewer component.
     *
     * @return \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer
     */
    public function getModelViewerComponent(): CrudModelViewer;
}
