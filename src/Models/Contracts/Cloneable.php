<?php

namespace Softworx\RocXolid\Models\Contracts;

use Illuminate\Support\Collection;

interface Cloneable
{
    // @todo refactor
    public function clone(Collection &$clone_log, array $fill = [], array $with_relations = []): Cloneable;
}
