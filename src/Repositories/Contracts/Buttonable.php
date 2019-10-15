<?php

namespace Softworx\RocXolid\Repositories\Contracts;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Repositories\Contracts\Column;

interface Buttonable
{
    public function addButton(Column $column_type): Buttonable;

    public function getButtons(): Collection;

    public function getButton($name): Column;
}
