<?php

namespace Softworx\RocXolid\Repositories\Contracts;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Repositories\Contracts\Column;

interface Columnable
{
    public function addColumn(Column $column): Columnable;

    public function setColumns($columns): Columnable;

    public function getColumns(): Collection;

    public function getColumn($name): Column;
}
