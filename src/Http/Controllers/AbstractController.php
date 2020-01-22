<?php

namespace Softworx\RocXolid\Http\Controllers;

use Illuminate\Routing\Controller as IlluminateController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Routable;
use Softworx\RocXolid\Contracts\TranslationPackageProvider;
use Softworx\RocXolid\Contracts\TranslationParamProvider;
// rocXolid traits
use Softworx\RocXolid\Traits\TranslationPackageProvider as TranslationPackageProviderTrait;
use Softworx\RocXolid\Traits\TranslationParamProvider as TranslationParamProviderTrait;
// rocXolid controller traits
use Softworx\RocXolid\Http\Controllers\Traits\Utils;

/**
 * Base rocXolid controller.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractController extends IlluminateController implements TranslationPackageProvider, TranslationParamProvider
{
    use AuthorizesRequests;
    use TranslationPackageProviderTrait;
    use TranslationParamProviderTrait;
    use Utils\CreatesRoutes;
    use Utils\Translates;
}
