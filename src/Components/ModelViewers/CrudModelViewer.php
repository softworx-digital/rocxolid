<?php

namespace Softworx\RocXolid\Components\ModelViewers;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid components
use Softworx\RocXolid\Components\AbstractActiveComponent;
// rocXolid component contracts
use Softworx\RocXolid\Components\Contracts\Formable;
use Softworx\RocXolid\Components\Contracts\Componentable\Form as FormComponentable;
// rocXolid controllers
use Softworx\RocXolid\Http\Controllers\AbstractCrudController;

/**
 *
 */
class CrudModelViewer extends AbstractActiveComponent implements FormComponentable
{
    protected $model_form_component = null;

    public function setFormComponent(Formable $model_form_component): FormComponentable
    {
        $this->model_form_component = $model_form_component;

        return $this;
    }

    public function getFormComponent(): Formable
    {
        if (is_null($this->model_form_component)) {
            throw new \RuntimeException(sprintf('CRUD model_form_component not yet set to [%s]', get_class($this)));
        }

        return $this->model_form_component;
    }

    public function adjustCreate(CrudRequest $request, AbstractCrudController $controller): CrudModelViewer
    {
        return $this;
    }

    public function adjustUpdate(CrudRequest $request, AbstractCrudController $controller): CrudModelViewer
    {
        return $this;
    }

    public function adjustShow(CrudRequest $request, AbstractCrudController $controller): CrudModelViewer
    {
        return $this;
    }
}
