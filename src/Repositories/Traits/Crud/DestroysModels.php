<?php

namespace Softworx\RocXolid\Repositories\Traits\Crud;

// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;

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
    public function deleteModel(CrudableModel $model): CrudableModel
    {
        if (!$model->canBeDeleted()) {
            throw new \RuntimeException(sprintf('Model [%s]:[%s] cannot be deleted', (new \ReflectionClass($model))->getName(), $model->getKey()));
        }

        return tap($model)
            ->beforeDelete()
            ->delete()
            ->afterDelete();
    }

    /**
     * {@inheritDoc}
     */
    public function forceDeleteModel(CrudableModel $model): CrudableModel
    {
        if (!$model->canBeDeleted()) {
            throw new \RuntimeException(sprintf('Model [%s]:[%s] cannot be deleted', (new \ReflectionClass($model))->getName(), $model->getKey()));
        }

        return tap($model)
            ->beforeDelete()
            ->forceDelete()
            ->afterDelete();
    }
}