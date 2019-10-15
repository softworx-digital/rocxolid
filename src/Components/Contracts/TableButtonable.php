<?php

namespace Softworx\RocXolid\Components\Contracts;

use Softworx\RocXolid\Repositories\Contracts\Column as TableButtonContract;

// @TODO - zatial sa dava buttonanchor implementujuci Column (tuto aliasnuty ako TableButtonContract) - toto doladit / rozdelit
interface TableButtonable
{
    public function setButton(TableButtonContract $button): TableButtonable;

    public function getButton();
}
