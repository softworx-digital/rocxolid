<?php

namespace Softworx\RocXolid\Contracts;

/**
 * Enables object to provide translation key.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface TranslationKeyProvider
{
    /**
     * Provide the translation key.
     *
     * @return string
     * @throws \UnderflowException If no translation key is set.
     */
    public function provideTranslationKey(): string;

    /**
     * Check if the object has a translation key defined.
     *
     * @return bool
     */
    public function issetTranslationKey(): bool;
}
