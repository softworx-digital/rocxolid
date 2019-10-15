<?php

namespace Softworx\RocXolid\Models\Contracts;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Models\Contracts\Container;

/**
 *
 */
interface Containee
{
    public function getContainer(string $container_relation_name): Container;

    public function getContainers(string $container_relation_name): Collection;

    public function hasContainer(string $container_relation_name, Container $container = null): bool;

    public function getContaineePivotTable(): string;

    public function setContaineePivotData($pivot_data): Containee;

    public function getContaineePivotData(): \stdClass;
}
