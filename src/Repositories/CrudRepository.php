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
    public function init(string $model_type): Crudable
    {
        $this->query_model = app($model_type);

        if (!($this->query_model instanceof CrudableModel)) {
            throw new \InvalidArgumentExcption();
        }

        return $this;
    }
}
