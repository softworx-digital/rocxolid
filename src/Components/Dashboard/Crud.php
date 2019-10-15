<?php

namespace Softworx\RocXolid\Components\Dashboard;

use Softworx\RocXolid\Components\AbstractActiveComponent;
use Softworx\RocXolid\Components\Contracts\Repositoryable;
use Softworx\RocXolid\Components\Contracts\RepositoryComponentable;
use Softworx\RocXolid\Components\Contracts\ModelViewerComponentable;
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer;

class Crud extends AbstractActiveComponent implements RepositoryComponentable, ModelViewerComponentable
{
    protected $repository_component = null;

    protected $model_viewer_component = null;

    public function setRepositoryComponent(Repositoryable $component): RepositoryComponentable
    {
        $this->repository_component = $component;

        return $this;
    }

    public function getRepositoryComponent(): Repositoryable
    {
        if (is_null($this->repository_component)) {
            throw new \RuntimeException(sprintf('CRUD table / repository_component not yet set to [%s]', get_class($this)));
        }

        return $this->repository_component;
    }

    public function setModelViewerComponent(CrudModelViewer $model_viewer_component): ModelViewerComponentable
    {
        $this->model_viewer_component = $model_viewer_component;

        return $this;
    }

    public function getModelViewerComponent(): CrudModelViewer
    {
        if (is_null($this->model_viewer_component)) {
            throw new \RuntimeException(sprintf('CRUD model_viewer_component not yet set to [%s]', get_class($this)));
        }

        return $this->model_viewer_component;
    }
}
