<?php

namespace Softworx\RocXolid\Models\Traits\OnActions;

use Illuminate\Support\Collection;

/**
 * @todo: subject to refactoring
 */
trait RepositoryActions
{
    public function fillCustom(Collection $data, string $action)
    {
        return $this;
    }

    public function onCreateBeforeSave(Collection $data, string $action)
    {
        return $this;
    }

    public function onCreateAfterSave(Collection $data, string $action)
    {
        return $this;
    }

    public function onCreateFinish(Collection $data, string $action)
    {
        return $this;
    }

    public function onUpdateBeforeSave(Collection $data, string $action)
    {
        return $this;
    }

    public function onUpdateAfterSave(Collection $data, string $action)
    {
        return $this;
    }

    public function onUpdateFinish(Collection $data, string $action)
    {
        return $this;
    }

    public function beforeDelete(string $action)
    {
        return $this;
    }

    public function afterDelete(string $action)
    {
        return $this;
    }
}
