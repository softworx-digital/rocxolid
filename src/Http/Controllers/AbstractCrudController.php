<?php

namespace Softworx\RocXolid\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Modellable;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\Repository;
// rocXolid utils
use Softworx\RocXolid\Http\Responses\Contracts\AjaxResponse;
// rocXolid controller contracts
use Softworx\RocXolid\Http\Controllers\Contracts\Crudable;
use Softworx\RocXolid\Http\Controllers\Contracts\Dashboardable;
use Softworx\RocXolid\Http\Controllers\Contracts\Repositoryable;
// rocXolid component contracts
use Softworx\RocXolid\Components\Contracts\Repositoryable as RepositoryableComponent;
// rocXolid traits
use Softworx\RocXolid\Traits\Modellable as ModellableTrait;
// rocXolid components
use Softworx\RocXolid\Components\Tables\CrudTable as CrudTableComponent;
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer as CrudModelViewerComponent;

/**
 * Base rocXolid controller for CRUD (and associated) operations.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo: add contracts to repository features traits
 * @todo: cleanup
 */
abstract class AbstractCrudController extends AbstractController implements Crudable, Dashboardable, Repositoryable, Modellable
{
    use ModellableTrait; // @todo: consider different approach
    use Traits\Crudable;
    use Traits\Dashboardable;
    use Traits\Repositoryable;
    use Traits\RepositoryOrderable;
    use Traits\RepositoryFilterable;
    use Traits\RepositoryAutocompleteable; // @todo: consider different approach
    use Traits\ItemsReorderderable; // @todo: add only where needed
    use Traits\Actions\SwitchesEnability;
    use Traits\Actions\ReloadsForm;
    use Traits\Actions\ReloadsFormGroup;
    use Traits\Actions\ValidatesFormGroup;
    use Traits\Actions\ClonesModels;

    /**
     * Model repository reference.
     *
     * @var \Softworx\RocXolid\Repositories\Contracts\Repository
     */
    protected $repository;

    /**
     * Response container reference.
     *
     * @var \Softworx\RocXolid\Http\Responses\Contracts\AjaxResponse
     */
    protected $response;

    /**
     * <controller-action> => <form-param>
     * or
     * <controller-action>.<section> => <form-param>
     *
     * @var array
     */
    protected $form_mapping = [
        'create' => 'create',
        'store' => 'create',
        'edit' => 'update',
        'update' => 'update',
    ];

    /**
     * Constructor.
     *
     * @param \Softworx\RocXolid\Repositories\Contracts\Repository
     * @param \Softworx\RocXolid\Http\Responses\Contracts\AjaxResponse $response
     */
    public function __construct(Repository $repository, AjaxResponse $response)
    {
        $this->repository = $repository;
        $this->response = $response;

        $this->authorizeResource(static::getModelClass(), static::getModelClass()::getAuthorizationParameter());
    }

    /**
     * Retrieve the response that is going to be send.
     *
     * @return \Softworx\RocXolid\Http\Responses\Contracts\AjaxResponse
     */
    public function getResponse(): AjaxResponse
    {
        return $this->response;
    }

    /**
     * Dynamically create route for given controller action.
     * Set model as a first parameter to the route if given.
     *
     * @param string $route_action
     * @return string
     */
    public function getRoute(string $route_action, ...$params): string
    {
        $action = sprintf('\%s@%s', get_class($this), $route_action);
        $action_params = [];

        array_walk($params, function ($param) use (&$action_params) {
            if (is_array($param)) {
                $action_params += $param;
            } else {
                $action_params[] = $param;
            }
        });

        return action($action, $action_params);
    }

    /**
     * Retrieve repository component to show.
     *
     * @param \Softworx\RocXolid\Repositories\Contracts\Repository $repository
     * @return \Softworx\RocXolid\Components\Contracts\Repositoryable
     */
    public function getRepositoryComponent(Repository $repository): RepositoryableComponent
    {
        return CrudTableComponent::build($this, $this)
            ->setRepository($repository);
    }

    /**
     * Retrieve model viewer component to show.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @return \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer
     */
    public function getModelViewerComponent(CrudableModel $model): CrudModelViewerComponent
    {
        return CrudModelViewerComponent::build($this, $this)
            ->setModel($model)
            ->setController($this);
    }

    /**
     * Naively guess the translation param for components based on controllers namespace.
     *
     * @return string
     */
    protected function guessTranslationParam(): ?string
    {
        $reflection = new \ReflectionClass($this);
        $param = last(explode('\\', $reflection->getNamespaceName()));

        return Str::kebab($param);
    }
}
