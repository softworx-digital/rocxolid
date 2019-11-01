<?php

namespace Softworx\RocXolid\Contracts;

/**
 * Enables object to provide translation package.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface TranslationPackageProvider
{
    /**
     * Provide the translation package.
     * 
     * @return string
     * @throws \UnderflowException If no translation package is set.
     */
    public function provideTranslationPackage(): string;

    /**
     * Check if the object has a translation package defined.
     *
     * @return bool
     */
    public function issetTranslationPackage(): bool;
}
