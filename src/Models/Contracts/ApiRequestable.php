<?php

namespace Softworx\RocXolid\Models\Contracts;

use Softworx\RocXolid\Http\Requests\CrudRequest;

/**
 * Enables the model to respond to API requests.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface ApiRequestable
{
    /**
     * Run specific actions on model before the model is returned to API.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @return \Softworx\RocXolid\Models\Contracts\ApiRequestable
     */
    public function onShowResponse(CrudRequest $request);//: ApiRequestable;

    /**
     * Run specific actions on model before the model is returned to API after create.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @return \Softworx\RocXolid\Models\Contracts\ApiRequestable
     */
    public function onStoreResponse(CrudRequest $request): ApiRequestable;

    /**
     * Run specific actions on model before the model is returned to API after update.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @return \Softworx\RocXolid\Models\Contracts\ApiRequestable
     */
    public function onUpdateResponse(CrudRequest $request): ApiRequestable;

    /**
     * Run specific actions on model before the model is returned to API after destroy.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @return \Softworx\RocXolid\Models\Contracts\ApiRequestable
     */
    public function onDestroyResponse(CrudRequest $request): ApiRequestable;
}
