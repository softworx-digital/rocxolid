<?php

namespace Softworx\RocXolid\Http\Controllers;

use Illuminate\Routing\Controller as IlluminateController;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Routable;
use Softworx\RocXolid\Contracts\TranslationPackageProvidable;
// rocXolid traits
use Softworx\RocXolid\Http\Controllers\Traits\Actionable as ActionableTrait;
use Softworx\RocXolid\Traits\TranslationPackageProvidable as TranslationPackageProvidableTrait;

abstract class AbstractController extends IlluminateController implements TranslationPackageProvidable
{
    use ActionableTrait;
    use TranslationPackageProvidableTrait;
}
