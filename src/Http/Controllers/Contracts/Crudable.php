<?php

namespace Softworx\RocXolid\Http\Controllers\Contracts;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Softworx\RocXolid\Http\Requests\CrudRequest;

//@todo - check & update if needed
interface Crudable
{
    public function index(CrudRequest $request);//: View;

    public function create(CrudRequest $request);

    public function store(CrudRequest $request);//: Response;

    public function edit(CrudRequest $request, $id);

    public function update(CrudRequest $request, $id);//: Response;

    public function show(CrudRequest $request, $id);

    public function destroyConfirm(CrudRequest $request, $id);

    public function destroy(CrudRequest $request, $id);//: Response; - pri ajaxe vracia JSON
}
