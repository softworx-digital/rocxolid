<?php

namespace Softworx\RocXolid\Components\Contracts;

use Illuminate\Support\Collection;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Table;

// @todo: finish
interface Tableable
{
    public function setTable(Table $repository): Tableable;

    public function getTable(): Table;

    public function getTableColumnsComponents(): Collection;

    public function getTableButtonsComponents(): Collection;
}
