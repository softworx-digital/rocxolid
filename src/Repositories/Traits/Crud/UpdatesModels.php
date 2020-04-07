<?php

namespace Softworx\RocXolid\Repositories\Traits\Crud;

use Illuminate\Support\Collection;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to update a CRUDable model instance.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait UpdatesModels
{
    /**
     * {@inheritDoc}
     */
    public function updateModel(Crudable $model, Collection $data): Crudable
    {
        $model = $this
            ->fillModel($model, $data)
            ->onUpdateBeforeSave($data);

        // save returns bool so tapping
        return tap($model)
            ->save()
            ->onUpdateAfterSave($data)
            ->fillRelationships($data)
            ->onUpdated($data);
    }
}