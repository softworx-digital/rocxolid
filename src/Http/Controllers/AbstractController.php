<?php

namespace Softworx\RocXolid\Http\Controllers;

use Illuminate\Routing\Controller as IlluminateController;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Routable;
use Softworx\RocXolid\Contracts\TranslationPackageProvider;
use Softworx\RocXolid\Contracts\TranslationParamProvider;
// rocXolid traits
use Softworx\RocXolid\Http\Controllers\Traits\Actionable as ActionableTrait;
use Softworx\RocXolid\Traits\TranslationPackageProvider as TranslationPackageProviderTrait;
use Softworx\RocXolid\Traits\TranslationParamProvider as TranslationParamProviderTrait;

abstract class AbstractController extends IlluminateController implements TranslationPackageProvider, TranslationParamProvider
{
    use ActionableTrait;
    use TranslationPackageProviderTrait;
    use TranslationParamProviderTrait;
}
