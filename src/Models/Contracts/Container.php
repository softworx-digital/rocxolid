<?php

namespace Softworx\RocXolid\Models\Contracts;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Models\Contracts\Containee;

interface Container
{
    public function getAllContainees(): Collection;

    public function getContainees(string $relation_name): Collection;

    public function attachContainee(string $relation_name, Containee $containee, $position = null, $parent_id = null): int;

    public function detachContainee(string $relation_name, Containee $containee = null): Container;

    public function hasContainee(string $relation_name, Containee $containee = null): bool;

    public function reorderContainees(string $relation_name, array $containee_order_data, $parent_id = null, $containee_type_param = 'containeeType', $containee_id_param = 'containeeId', $containee_children_param = 'children'): Container;

    public function getContainerPivotTable(): string;
}
