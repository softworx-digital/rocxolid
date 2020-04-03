<?php

namespace Softworx\RocXolid\Components\Contracts;

use Softworx\RocXolid\Tables\Contracts\Button;

// @todo: docblocks
interface TableButtonable
{
    public function setButton(Button $button): TableButtonable;

    public function getButton();
}
