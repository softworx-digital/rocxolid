<?php

namespace Softworx\RocXolid\Contracts;

/**
 * Enables object to provide fully quallified translation key.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface TranslationDiscoveryProvider extends TranslationPackageProvider, TranslationParamProvider, TranslationKeyProvider
{
}
