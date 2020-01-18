<?php

namespace Softworx\RocXolid\Http\Controllers\Contracts;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;

//@todo - check & update if needed
interface Crudable
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
