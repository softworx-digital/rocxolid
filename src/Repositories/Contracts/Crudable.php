<?php

namespace Softworx\RocXolid\Repositories\Contracts;

use Illuminate\Support\Collection;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;

/**
 * Enables repository to make CRUD operations on model.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Crudable extends Repository
{
    /**
     * Create a model instance filled with provided data.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Illuminate\Support\Collection $data
     * @return \Softworx\RocXolid\Models\Contracts\Crudable
     */
    public function fillModel(CrudableModel $model, Collection $data): CrudableModel;

    /**
     * Create a model instance filled with provided data.
     *
     * @param \Illuminate\Support\Collection $data
     * @return \Softworx\RocXolid\Models\Contracts\Crudable
     */
    public function createModel(Collection $data): CrudableModel;

    /**
     * Get a model instance by its key.
     *
     * @param mixed $key
     * @return \Softworx\RocXolid\Models\Contracts\Crudable
     */
    public function readModel($key): CrudableModel;

    /**
     * Update model instance with provided data.
     * Updates direct model data and relations.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Illuminate\Support\Collection $data
     * @return \Softworx\RocXolid\Models\Contracts\Crudable
     */
    public function updateModel(CrudableModel $model, Collection $data): CrudableModel;

    /**
     * Delete a model instance (remains persisted if uses SofDelete).
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @return \Softworx\RocXolid\Models\Contracts\Crudable
     */
    public function deleteModel(CrudableModel $model): CrudableModel;

    /**
     * Force delete a model instance from storage.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @return \Softworx\RocXolid\Models\Contracts\Crudable
     */
    public function forceDeleteModel(CrudableModel $model): CrudableModel;

    /**
     * Clone model instance with provided additional data.
     * Clones direct model data and relations.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Illuminate\Support\Collection $data
     * @return \Softworx\RocXolid\Models\Contracts\Crudable
     */
    public function cloneModel(CrudableModel $model, Collection $data): CrudableModel;
}
