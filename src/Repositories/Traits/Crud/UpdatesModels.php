<?php

namespace Softworx\RocXolid\Repositories\Traits\Crud;

use Illuminate\Support\Collection;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;

/**
 * Trait to update a CRUDable model instance.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
// @todo: ugly > needs a bit of refactoring
trait UpdatesModels
{
    public function fillModel(Collection $data, CrudableModel $model, string $action): CrudableModel
    {
        return $model
            ->fill($data, $action)
            ->fillCustom($data, $action)
            ->resolvePolymorphism($data, $action)
            ->beforeSave($data, $action);
    }

    /**
     * {@inheritDoc}
     */
    public function updateModel(Collection $data, CrudableModel $model, string $action): CrudableModel
    {
        $model = $this->fillModel($data, $model, $action);

        // @todo: "hotfixed"
        $model = $this
            ->beforeModelSave($model, $action);

        $model
            ->save();

        $model
            ->fillRelationships($data, $action)
            ->afterSave($data, $action);

        return $model;
    }

    protected function beforeModelSave(CrudableModel $model, string $action): CrudableModel
    {
        return $model;
    }
}