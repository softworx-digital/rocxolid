<?php

namespace Softworx\RocXolid\Repositories\Traits\Crud;

use Illuminate\Support\Collection;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to clone a CRUDable model instance.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ClonesModels
{
    /**
     * {@inheritDoc}
     */
    public function cloneModel(Crudable $model, Collection $data): Crudable
    {
        // @todo temporary solution
        if ($data->has('_clone_with_relations')) {
            $with_relations = $data->get('_clone_with_relations') ?: collect();
        } else {
            $with_relations = collect($this->getModel()->getCloneRelationshipMethods());
        }
        $clone_log = collect();

        $clone = $model->clone($clone_log, [], $with_relations->toArray());

        $clone = $this
            ->fillModel($clone, $data);

        return tap($clone)
            ->save();

        /*
        $model = $this
            ->fillModel($model, $data)
            ->onBeforeSave($data)
            ->onCloneBeforeSave($data);

        // save returns bool so tapping
        return tap($model)
            ->save()
            ->onAfterSave($data)
            ->onCloneAfterSave($data)
            ->fillRelationships($data)
            ->onCloned($data);
        */
    }
}
