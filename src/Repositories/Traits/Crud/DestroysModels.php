<?php

namespace Softworx\RocXolid\Repositories\Traits\Crud;

// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to destroy a CRUDable model instance.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait DestroysModels
{
    /**
     * {@inheritDoc}
     */
    public function deleteModel(Crudable $model): Crudable
    {
        $model = $model->beforeDelete();

        return tap($model)
            ->delete()
            ->afterDelete();
    }

    /**
     * {@inheritDoc}
     */
    public function forceDeleteModel(Crudable $model): Crudable
    {
        $model = $model->beforeDelete();

        return tap($model)
            ->forceDelete()
            ->afterDelete();
    }
}
