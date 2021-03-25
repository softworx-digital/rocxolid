<?php

namespace Softworx\RocXolid\Models\Traits;

use Softworx\RocXolid\Http\Requests\CrudRequest;
use Softworx\RocXolid\Models\Contracts\ApiRequestable as ApiRequestableContract;

/**
 * Trait to enable the model to respond to API requests.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ApiRequestable
{
    /**
     * {@inheritDoc}
     */
    public function onShowResponse(CrudRequest $request)//: ApiRequestableContract
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function onStoreResponse(CrudRequest $request): ApiRequestableContract
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function onUpdateResponse(CrudRequest $request): ApiRequestableContract
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function onDestroyResponse(CrudRequest $request): ApiRequestableContract
    {
        return $this;
    }
}
