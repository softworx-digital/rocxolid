<?php

namespace Softworx\RocXolid\Repositories\Traits\Crud;

use Illuminate\Support\Collection;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;

/**
 * Trait to create a CRUDable model instance.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait CreatesModels
{
    /**
     * {@inheritDoc}
     */
    public function createModel(Collection $data): CrudableModel
    {
        return $this
            ->getModel()
            ->create($data);
    }
}