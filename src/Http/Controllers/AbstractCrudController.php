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
// rocXolid traits
use Softworx\RocXolid\Traits\Repositoryable;
use Softworx\RocXolid\Traits\Modellable;

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
    use Modellable; // @todo: consider different approach
    use Repositoryable;
    use Traits\Crudable;
    use Traits\Dashboardable;
    use Traits\Tableable;
    use Traits\Formable;
    use Traits\Components\TableComponentable;
    use Traits\Components\FormComponentable;
    use Traits\Components\ModelViewerComponentable;
    use Traits\Actions\ItemsReorderderable; // @todo: add only where needed
    use Traits\Actions\SwitchesEnability;
    use Traits\Actions\ClonesModels;
    use Traits\Actions\Table\OrdersTable;
    use Traits\Actions\Table\FiltersTable;
    use Traits\Actions\Form\ReloadsForm;
    use Traits\Actions\Form\ReloadsFormGroup;
    use Traits\Actions\Form\ValidatesFormGroup;
    use Traits\Actions\Form\RepositoryAutocompleteable; // @todo: consider different approach !!

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
}
