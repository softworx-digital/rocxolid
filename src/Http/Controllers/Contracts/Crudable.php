<?php

namespace Softworx\RocXolid\Http\Controllers\Contracts;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
use Softworx\RocXolid\Http\Responses\Contracts\Response;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Responseable;
use Softworx\RocXolid\Contracts\Repositoryable;
use Softworx\RocXolid\Contracts\Modellable;
// rocXolid controller contracts
use Softworx\RocXolid\Http\Controllers\Contracts\Dashboardable;
use Softworx\RocXolid\Http\Controllers\Contracts\Tableable;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;

// @todo: revise & update if needed
// @todo: add responses (after model argument addition to destroyResponse)
/**
 * Enables controller to handle all the CRUD operations and give appropriate response.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Crudable extends Responseable, Repositoryable, Modellable, Dashboardable, Tableable
{
    public function index(CrudRequest $request);//: View;

    public function create(CrudRequest $request);

    public function store(CrudRequest $request);//: Response;

    public function edit(CrudRequest $request, CrudableModel $model);

    public function update(CrudRequest $request, CrudableModel $model);//: Response;

    public function show(CrudRequest $request, CrudableModel $model);

    public function destroyConfirm(CrudRequest $request, CrudableModel $model);

    public function destroy(CrudRequest $request, CrudableModel $model);//: Response; - pri ajaxe vracia JSON
}
