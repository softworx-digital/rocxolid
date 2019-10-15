<?php

namespace Softworx\RocXolid\Components\Contracts;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Repositories\Contracts\Repository;

interface Repositoryable
{
    public function setRepository(Repository $repository): Repositoryable;

    public function getRepository(): Repository;

    public function getTableColumnsComponents(): Collection;

    public function getTableButtonsComponents(): Collection;
}
