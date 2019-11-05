<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

use App;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
use Softworx\RocXolid\Communication\Contracts\AjaxResponse;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Repositories\Contracts\Repositoryable;
// rocXolid forms
use Softworx\RocXolid\Forms\AbstractCrudForm as AbstractCrudForm;
// rocXolid components
use Softworx\RocXolid\Components\AbstractActiveComponent;
use Softworx\RocXolid\Components\General\Message;
use Softworx\RocXolid\Components\Forms\FormField;
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;
// @TODO: try to separate this from general trait
use Softworx\RocXolid\Common\Repositories\File\Repository as FileRepository;
use Softworx\RocXolid\Common\Repositories\Image\Repository as ImageRepository;

/**
 *
 */
trait Crudable
{
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

    public function isModelActionAvailable(CrudableModel $model, $action)
    {
        return true;
    }

    public function index(CrudRequest $request)//: View
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));
        $repository_component = $this->getRepositoryComponent($repository);

        if ($request->ajax()) {
            return $this->response
                ->replace($repository_component->getDomId(), $repository_component->fetch())
                ->get();
        } else {
            return $this
                ->getDashboard()
                ->setRepositoryComponent($repository_component)
                ->render('index');
        }
    }

    /**
     * Reload Create/Update form to dynamically load related field values.
     */
    public function formReload(CrudRequest $request, $id = null)//: Response
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $model = $id ? $repository->findOrFail($id) : $repository->getModel();

        $this->setModel($model);

        // @TODO: refactor to clearly identify the form we want to get, not artifically like this
        // put form->options['route-action'], or full identification data into the request
        // this can serve as a fallback
        if ($model->exists) {
            $form = $repository
                ->getForm($this->getFormParam($request, 'update'))
                    ->setFieldsRequestInput($request->input())
                    ->adjustUpdateBeforeSubmit($request);
        } else {
            $form = $repository
                ->getForm($this->getFormParam($request, 'create'))
                    ->setFieldsRequestInput($request->input())
                    ->adjustCreateBeforeSubmit($request);
        }

        $form_component = CrudFormComponent::build($this, $this)
                ->setForm($form)
                ->setRepository($repository);

        return $this->response
                ->replace($form_component->getDomId('fieldset'), $form_component->fetch('include.fieldset'))
                ->get();
    }

    /**
     * Show model create screen.
     * 
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest
     */
    public function create(CrudRequest $request)
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->getModel());

        $form = $repository->getForm($this->getFormParam($request));
        $form
            ->adjustCreate($request);

        $form_component = CrudFormComponent::build($this, $this)
            ->setForm($form)
            ->setRepository($repository);

        $model_viewer_component = $this
            ->getModelViewerComponent($this->getModel())
            ->setFormComponent($form_component)
            ->adjustCreate($request, $this);

        if ($request->ajax()) {
            return $this->response
                ->modal($model_viewer_component->fetch('modal.create'))
                ->get();
        } else {
            return $this
                ->getDashboard()
                ->setModelViewerComponent($model_viewer_component)
                ->render('model', [
                    'model_viewer_template' => 'create'
                ]);
        }
    }

    /**
     * Store the created model.
     * 
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest
     */
    public function store(CrudRequest $request)//: Response
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->getModel());

        $form = $repository->getForm($this->getFormParam($request));
        $form
            //->adjustCreate($request)
            ->adjustCreateBeforeSubmit($request)
            ->submit();

        if ($form->isValid()) {
            return $this->success($request, $repository, $form, 'create');
        } else {
            return $this->errorResponse($request, $repository, $form, 'create');
        }
    }

    /**
     * Show model edit screen.
     * 
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest
     */
    public function edit(CrudRequest $request, $id)
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

        $form = $repository->getForm($this->getFormParam($request));
        $form
            ->adjustUpdate($request);

        $form_component = CrudFormComponent::build($this, $this)
            ->setForm($form)
            ->setRepository($repository);

        $model_viewer_component = $this
            ->getModelViewerComponent($this->getModel())
            ->setFormComponent($form_component)
            ->adjustUpdate($request, $this);

        if ($request->ajax()) {
            return $this->response
                ->modal($model_viewer_component->fetch('modal.update'))
                ->get();
        } else {
            return $this
                ->getDashboard()
                ->setModelViewerComponent($model_viewer_component)
                ->render('model', [
                    'model_viewer_template' => 'update'
                ]);
        }
    }

    /**
     * Update the edited model.
     * 
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest
     */
    public function update(CrudRequest $request, $id)//: Response
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

        $form = $repository->getForm($this->getFormParam($request));
        $form
            //->adjustUpdate($request)
            ->adjustUpdateBeforeSubmit($request)
            ->submit();

        if ($form->isValid()) {
            return $this->success($request, $repository, $form, 'update');
        } else {
            return $this->errorResponse($request, $repository, $form, 'update');
        }
    }

    public function show(CrudRequest $request, $id)
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

        $model_viewer_component = $this
            ->getModelViewerComponent($this->getModel())
            ->adjustShow($request, $this);

        if ($request->ajax()) {
            return $this->response
                ->modal($model_viewer_component->fetch('modal.show'))
                ->get();
        } else {
            return $this
                ->getDashboard()
                ->setModelViewerComponent($model_viewer_component)
                ->render('model', [
                    'model_viewer_template' => 'show'
                ]);
        }
    }

    public function cloneConfirm(CrudRequest $request, $id)
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

        $model_viewer_component = $this->getModelViewerComponent($this->getModel());

        if ($request->ajax()) {
            return $this->response
                ->modal($model_viewer_component->fetch('modal.clone-confirm'))
                ->get();
        } else {
            return redirect($this->getRoute('edit', $this->getModel()));
        }
    }

    public function clone(CrudRequest $request, $id)
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

        $with_relations = $request->input('_data.with_relations', []);
        $clone_log = new Collection();
        $clone = $this->getModel()->clone($clone_log, [], $with_relations);

        if ($request->ajax()) {
            return $this->response->redirect($this->getRoute('edit', $clone))->get();
        } else {
            return redirect($this->getRoute('edit', $clone));
        }
    }

    public function destroyConfirm(CrudRequest $request, $id)
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

        $model_viewer_component = $this->getModelViewerComponent($this->getModel());

        if ($request->ajax()) {
            return $this->response
                ->modal($model_viewer_component->fetch('modal.destroy-confirm'))
                ->get();
        } else {
            return $this
                ->getDashboard()
                ->setModelViewerComponent($model_viewer_component)
                ->render('model', [
                    'model_viewer_template' => 'destroy-confirm'
                ]);
        }
    }

    public function destroy(CrudRequest $request, $id)//: Response - returns JSON for ajax calls
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

        $model = $repository->deleteModel($this->getModel());

        return $this->destroyResponse($request, $model);
    }

    public function fileUpload(CrudRequest $request, $id)
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

        $form = $repository->getForm('update');

        $model_viewer_component = $this
            ->getModelViewerComponent($this->getModel());

        $file_repository = App::make(FileRepository::class);

        foreach ($request->file() as $data) {
            foreach ($data as $field_name => $data_files) {
                foreach ($data_files as $data_file) {
                    $file = $file_repository->handleUpload($data_file, $this->getModel(), $field_name);
                }

                $form_field_component = (new FormField())->setFormField($form->getFormField($field_name));

                $this->response->replace($form_field_component->makeDomId('files', $field_name), $form_field_component->fetch('include.files'));
            }
        }

        return $this->response->get();
    }

    public function imageUpload(CrudRequest $request, $id)
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

        $form = $repository->getForm('update');

        $model_viewer_component = $this
            ->getModelViewerComponent($this->getModel());

        $image_repository = App::make(ImageRepository::class);

        foreach ($request->file() as $data) {
            foreach ($data as $field_name => $data_files) {
                foreach ($data_files as $data_file) {
                    $image = $image_repository->handleUpload($data_file, $this->getModel(), $field_name);
                }

                $form_field_component = (new FormField())->setFormField($form->getFormField($field_name));

                $this->response->replace($form_field_component->makeDomId('images', $field_name), $form_field_component->fetch('include.images'));
            }
        }

        return $this->response->get();
    }

    protected function getIndexViewAssignments(Request $request)//: array
    {
        return [];
    }

    protected function success(CrudRequest $request, Repository $repository, AbstractCrudForm $form, $action)
    {
        $model = $repository->updateModel($form->getFormFieldsValues()->toArray(), $this->getModel(), $action);

        return $this->successResponse($request, $repository, $form, $model, $action);
    }

    // @TODO: refactor to ease overrideability
    protected function successResponse(CrudRequest $request, Repository $repository, AbstractCrudForm $form, CrudableModel $model, string $action)
    {
        $form_component = CrudFormComponent::build($this, $this)
            ->setForm($form)
            ->setRepository($repository);

        $assignments = [];

        // @TODO: use constants rather than strings
        // @TODO: divide into <action> => <method> and wrap into property or class
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
                ->append($form_component->getDomId('output'), Message::build($this, $this)->fetch('crud.success', $assignments))
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
            return $this->response
                ->replace($form_component->getDomId('fieldset'), $form_component->fetch('include.fieldset'))
                ->get();
        } else {
            // @TODO: hotfixed, you can do better
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
