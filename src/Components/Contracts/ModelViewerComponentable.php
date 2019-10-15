<?php

namespace Softworx\RocXolid\Components\Contracts;

use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer;

interface ModelViewerComponentable
{
    public function setModelViewerComponent(CrudModelViewer $model_viewer_component): ModelViewerComponentable;

    public function getModelViewerComponent(): CrudModelViewer;
}
