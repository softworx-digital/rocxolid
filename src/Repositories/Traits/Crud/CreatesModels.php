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
    public function createModel(Collection $data, string $action): Crudable
    {
        $model = $this->getModel();

        // @todo: use tap
        $model->onCreateBeforeSave($data, $action);
        $model = $this->fillModel($model, $data, $action);
        $model->resolvePolymorphism($data, $action);
        $model->save();
        $model->onCreateAfterSave();
        $model->fillRelationships($data, $action);
        $model->onCreateFinish($data, $action);

        return $model;
    }
}