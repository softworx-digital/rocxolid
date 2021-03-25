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
    use Repositoryable;
    use Traits\Crudable;
    use Traits\Dashboardable;
    use Traits\Forms\HasForms;
    use Traits\Tables\HasTables;
    use Traits\Components\TableComponentable;
    use Traits\Components\FormComponentable;
    use Traits\Components\ModelViewerComponentable;
    use Traits\Actions\ReordersModels;
    use Traits\Actions\SwitchesEnability;
    use Traits\Actions\ClonesModels;

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
        'duplicate' => 'duplicate',
        'clone' => 'duplicate',
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
        // @todo !!! find some way to pass attribute to CrudPolicy::before() check
        // causes problems this way
        $this->authorizeResource(static::getModelType(), static::getModelType()::getAuthorizationParameter());

        $this
            ->setResponse($response)
            ->setRepository($repository->init(static::getModelType()))
            ->bindServices()
            ->init();
    }
}
