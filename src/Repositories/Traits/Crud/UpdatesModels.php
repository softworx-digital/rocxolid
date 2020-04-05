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
    public function updateModel(Crudable $model, Collection $data, string $action): Crudable
    {
        // @todo: use tap
        $model->onUpdateBeforeSave($data, $action);
        $model = $this->fillModel($model, $data, $action);
        $model->resolvePolymorphism($data, $action);
        $model->save();
        $model->onUpdateAfterSave($data, $action);
        $model->fillRelationships($data, $action);
        $model->onUpdateFinish($data, $action);

        return $model;
    }
}