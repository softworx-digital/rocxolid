<?php

namespace Softworx\RocXolid\Repositories\Traits\Crud;

use Illuminate\Support\Collection;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

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
    public function createModel(Collection $data): Crudable
    {
        // obtain new model instance
        $model = $this->getModel();

        $model = $this
            ->fillModel($model, $data)
            ->onBeforeSave($data)
            ->onCreateBeforeSave($data);

        // save returns bool so tapping
        return tap($model)
            ->save()
            ->onAfterSave($data)
            ->onCreateAfterSave($data)
            ->fillRelationships($data)
            ->onCreated($data);
    }
}