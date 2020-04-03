<?php

namespace Softworx\RocXolid\Http\Controllers;

// rocXolid services
use Softworx\RocXolid\Forms\Services\Contracts\FormService;
use Softworx\RocXolid\Tables\Services\Contracts\TableService;
// rocXolid utils
use Softworx\RocXolid\Http\Responses\Contracts\AjaxResponse;
// rocXolid controller contracts
use Softworx\RocXolid\Http\Controllers\Contracts\Crudable;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\Repository;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Table;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid component contracts
use Softworx\RocXolid\Components\Contracts\Tableable as TableableComponent;
// rocXolid traits
use Softworx\RocXolid\Traits\Responseable;
use Softworx\RocXolid\Traits\Repositoryable;
use Softworx\RocXolid\Traits\Modellable;
// rocXolid components
use Softworx\RocXolid\Components\Tables\CrudTable as CrudTableComponent;
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer as CrudModelViewerComponent;

/**
 * Base rocXolid controller for CRUD (and associated) operations.
 * This controller needs specific implementation for each CRUDable model.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractCrudController extends AbstractController implements Crudable
{
    use Responseable;
    use Modellable; // @todo: consider different approach
    use Repositoryable;
    use Traits\Crudable;
    use Traits\Dashboardable;
    use Traits\Tableable;
    use Traits\RepositoryOrderable;
    use Traits\RepositoryFilterable;
    use Traits\RepositoryAutocompleteable; // @todo: consider different approach
    use Traits\ItemsReorderderable; // @todo: add only where needed
    use Traits\Actions\SwitchesEnability;
    use Traits\Actions\ClonesModels;
    use Traits\Actions\ReloadsForm;
    use Traits\Actions\ReloadsFormGroup;
    use Traits\Actions\ValidatesFormGroup;

    /**
     * Mapping of form type params to controller actions.
     *
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
     * Mapping of table type params to controller actions.
     *
     * <controller-action> => <table-param>
     * or
     * <controller-action>.<section> => <table-param>
     *
     * @var array
     */
    protected $table_mapping = [
        'index' => 'index',
    ];

    /**
     * Default services used by controller.
     *
     * @var array
     */
    protected $default_services = [
        FormService::class,
        TableService::class,
    ];

    /**
     * Constructor.
     *
     * @param \Softworx\RocXolid\Http\Responses\Contracts\AjaxResponse $response
     * @param \Softworx\RocXolid\Repositories\Contracts\Repository
     */
    public function __construct(AjaxResponse $response, Repository $repository)
    {
        $this->authorizeResource(static::getModelType(), static::getModelType()::getAuthorizationParameter());

        $this
            ->setResponse($response)
            ->setRepository($repository->init(static::getModelType()))
            ->bindServices()
            ->init();
    }

    /**
     * Retrieve model data table component to show.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Table $table
     * @return \Softworx\RocXolid\Components\Contracts\Tableable
     */
    public function getTableComponent(Table $table): TableableComponent
    {
        return CrudTableComponent::build($this, $this)
            ->setTable($table);
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
}
