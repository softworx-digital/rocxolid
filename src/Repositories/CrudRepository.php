<?php

namespace Softworx\RocXolid\Repositories;

// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\Crudable;
// rocXolid repositories
use Softworx\RocXolid\Repositories\Repository;

/**
 * CRUD Repository is responsible for handling model's CRUD data operations.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class CrudRepository extends Repository implements Crudable
{
    use Traits\Crudable;

    /**
     * {@inheritDoc}
     */
    protected function validateModelType(string $model_type): bool
    {
        return (new \ReflectionClass($model_type))->isSubclassOf(CrudableModel::class);
    }
}
