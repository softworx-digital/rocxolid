<?php

namespace Softworx\RocXolid\Contracts;

/**
 * Enables object to provide translation param.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface TranslationParamProvider
{
    /**
     * Provide the translation param.
     * 
     * @return string
     * @throws \UnderflowException If no translation param is set.
     */
    public function provideTranslationParam(): string;

    /**
     * Check if the object has a translation param defined.
     *
     * @return bool
     */
    public function issetTranslationParam(): bool;
}
