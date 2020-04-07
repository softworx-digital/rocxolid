<?php

namespace Softworx\RocXolid\Models\Traits\OnActions;

use Illuminate\Support\Collection;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;


/**
 * @todo: subject to refactoring
 */
trait RepositoryActions
{
    public function fillCustom(Collection $data): Crudable
    {
        return $this;
    }

    public function onCreateBeforeSave(Collection $data): Crudable
    {
        return $this;
    }

    public function onCreateAfterSave(Collection $data): Crudable
    {
        return $this;
    }

    public function onCreated(Collection $data): Crudable
    {
        return $this;
    }

    public function onUpdateBeforeSave(Collection $data): Crudable
    {
        return $this;
    }

    public function onUpdateAfterSave(Collection $data): Crudable
    {
        return $this;
    }

    public function onUpdated(Collection $data): Crudable
    {
        return $this;
    }

    public function beforeDelete(): Crudable
    {
        return $this;
    }

    public function afterDelete(): Crudable
    {
        return $this;
    }
}
