<?php

namespace Softworx\RocXolid\Repositories\Traits;

use Illuminate\Support\Collection;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;

/**
 * Trait to make CRUD operations on model.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Crudable
{
    use Crud\ListsModels;
    use Crud\CreatesModels;
    use Crud\ReadsModels;
    use Crud\UpdatesModels;
    use Crud\DestroysModels;

    /**
     * Fill model with given data.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param \Illuminate\Support\Collection $data
     * @return \Softworx\RocXolid\Models\Contracts\Crudable
     */
    public function fillModel(CrudableModel $model, Collection $data): CrudableModel
    {
        return $model
            ->fill($data->toArray())
            ->fillCustom($data)
            ->resolvePolymorphism($data);
    }
}
