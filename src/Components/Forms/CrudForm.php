<?php

namespace Softworx\RocXolid\Components\Forms;

use Softworx\RocXolid\Contracts\Repositoryable;
use Softworx\RocXolid\Traits\Repositoryable as RepositoryableTrait;

class CrudForm extends Form implements Repositoryable
{
    use RepositoryableTrait;
}
