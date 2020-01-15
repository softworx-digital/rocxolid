<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

use App;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
use Softworx\RocXolid\Http\Responses\Contracts\AjaxResponse;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\Repository;
// rocXolid controllers contracts
use Softworx\RocXolid\Http\Controllers\Contracts\Repositoryable;
// rocXolid forms
use Softworx\RocXolid\Forms\AbstractCrudForm as AbstractCrudForm;
// rocXolid components
use Softworx\RocXolid\Components\AbstractActiveComponent;
use Softworx\RocXolid\Components\Forms\FormField;
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;

/**
 * @todo: split into separate traits each having (ideally) one method for given action
 */
trait Crudable
{
    use Crud\ListsModels;
    use Crud\CreatesModels;
    use Crud\ReadsModels;
    use Crud\UpdatesModels;
    use Crud\DestroysModels;
    use Actions\ReloadsForm;
    use Actions\ReloadsFormGroup;
    use Actions\ClonesModels;

    // protected static $model_class; // should be defined in specific class

    protected $response;

    public function __construct(AjaxResponse $response)
    {
        $this->response = $response;
    }

    public function getModelClass(): string
    {
        return static::$model_class;
    }

    // @todo: maybe some other approach
    public function isModelActionAvailable(CrudableModel $model, $action)
    {
        return true;
    }

    protected function success(CrudRequest $request, Repository $repository, AbstractCrudForm $form, $action)
    {
        $model = $repository->updateModel($form->getFormFieldsValues()->toArray(), $this->getModel(), $action);

        return $this->successResponse($request, $repository, $form, $model, $action);
    }

    // @todo: refactor to ease overrideability
    protected function successResponse(CrudRequest $request, Repository $repository, AbstractCrudForm $form, CrudableModel $model, string $action)
    {
        $form_component = CrudFormComponent::build($this, $this)
            ->setForm($form)
            ->setRepository($repository);

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
                            $this->response->redirect($this->getRoute('edit', $this->getModel()));
                        break;
                        case 'update':
                            $this->response->replace($form_component->getDomId(), $form_component->fetch('update'));
                        break;
                    }
                break;
                case 'submit-show':
                    $this->response->redirect($this->getRoute('show', $this->getModel()));
                break;
                default:
                    $this->response->redirect($this->getRoute('index'));
            }

            return $this->response
                ->notifySuccess($form_component->translate('text.updated'))
                //->append($form_component->getDomId('output'), Message::build($this, $this)->fetch('crud.success', $assignments))
                ->get();
        } else {
            /*
                $route = $this->getModel()->exists
                        ? $this->getRoute($action, $this->getModel())
                        : $this->getRoute($action);
            */
            switch ($request->input('_submit-action')) {
                case 'submit-new':
                    $route = $this->getRoute('create');
                break;
                case 'submit-edit':
                    $route = $this->getRoute('edit', $this->getModel());
                break;
                case 'submit-show':
                    $route = $this->getRoute('show', $this->getModel());
                break;
                default:
                    $route = $this->getRoute('index');
            }

            return redirect($route)
                ->with($form->getSessionParam('errors'), $form->getErrors())
                ->with($form->getSessionParam('input'), $request->input());
        }
    }

    protected function errorResponse(CrudRequest $request, Repository $repository, AbstractCrudForm $form, $action)
    {
        $form_component = CrudFormComponent::build($this, $this)
            ->setForm($form)
            ->setRepository($repository);

        $assignments = [
            'errors' => $form->getErrors()
        ];

        if ($request->ajax()) {
            // @todo: refactor - some validation, etc...
            if ($request->has('_form_field_group')) {
                $form_field_group_component = $form_component->getFormFieldGroupsComponents()->get($request->input('_form_field_group'));

                return $this->response
                    ->notifyError($form_component->translate('text.form-error'))
                    ->replace(
                        $form_field_group_component->getDomId($form_field_group_component->getFormFieldGroup()->getName()),
                        $form_field_group_component->fetch($form_field_group_component->getOption('template', $form_field_group_component->getDefaultTemplateName()), ['show' => true])
                    )
                    ->get();
            } else {
                return $this->response
                    ->notifyError($form_component->translate('text.form-error'))
                    ->replace($form_component->getDomId('fieldset'), $form_component->fetch('include.fieldset'))
                    ->get();
            }
        } else {
            // @todo: hotfixed, you can do better
            if ($action == 'update') {
                $action = 'edit';
            }

            $route = $this->getModel()->exists
                   ? $this->getRoute($action, $this->getModel())
                   : $this->getRoute($action);

            return redirect($route)
                ->with($form->getSessionParam('errors'), $form->getErrors())
                ->with($form->getSessionParam('input'), $request->input());
        }
    }

    protected function destroyResponse(CrudRequest $request, CrudableModel $model)
    {
        if ($request->ajax()) {
            return $this->response->redirect($this->getRoute('index'))->get();
        } else {
            return redirect($this->getRoute('index'));
        }
    }

    protected function getFormParam(CrudRequest $request, $method = null)
    {
        if (is_null($method)) {
            list($controller, $method) = explode('@', $request->route()->getActionName());
        }

        if ($request->filled('_section')) {
            $method = sprintf('%s.%s', $method, $request->_section);

            if (isset($this->form_mapping[$method])) {
                return $this->form_mapping[$method];
            }
        }

        if (!isset($this->form_mapping[$method])) {
            throw new \InvalidArgumentException(sprintf('No controller [%s] form mapping for method [%s]', get_class($this), $method));
        }

        return $this->form_mapping[$method];
    }

    protected function getRepositoryParam(CrudRequest $request, $default = Repositoryable::REPOSITORY_PARAM)
    {
        list($controller, $method) = explode('@', $request->route()->getActionName());
        /*
        if ($request->filled('_section'))
        {
            $method = sprintf('%s.%s', $method, $request->_section);

            if (isset($this->repository_mapping[$method]))
            {
                return $this->repository_mapping[$method];
            }
        }
        */
        if (isset($this->repository_mapping[$method])) {
            return $this->repository_mapping[$method];
        } elseif (!is_null($default)) {
            return $default;
        } elseif (empty($this->repository_mapping)) {
            return Repositoryable::REPOSITORY_PARAM;
        }

        throw new \InvalidArgumentException(sprintf('No controller [%s] repository mapping for method [%s]', get_class($this), $method));
    }
}
