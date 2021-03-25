<?php

namespace Softworx\RocXolid\Repositories\Traits\Crud;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;

/**
 * Trait to list CRUDable model instances.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ListsModels
{
    /**
     * {@inheritDoc}
     */
    public function listModels(): EloquentCollection
    {
        return $this->all();
    }
}
