<?php

namespace Softworx\RocXolid\Components\Contracts;

use Softworx\RocXolid\Tables\Columns\Contracts\Column;

interface TableColumnable
{
    public function setTableColumn(Column $table_column): TableColumnable;

    public function getTableColumn();
}
