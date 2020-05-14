<?php

namespace Softworx\RocXolid\Repositories\Traits\Crud;

// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;

/**
 * Trait to get - read a CRUDable model instance.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ReadsModels
{
    /**
     * {@inheritDoc}
     */
    public function readModel($key): CrudableModel
    {
        return $this->find($key);
    }
}