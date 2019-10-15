<?php

namespace Softworx\RocXolid\Components\Contracts;

use Softworx\RocXolid\Repositories\Contracts\Column;

interface TableColumnable
{
    public function setTableColumn(Column $table_column): TableColumnable;

    public function getTableColumn();
}
