<?php

namespace Softworx\RocXolid\Contracts;

/**
 * Enables object to translate language keys.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Translatable
{
    /**
     * Translate the language key.
     *
     * @return string
     */
    public function translate(string $key): string;

    /**
     * Set the package responsible for translation.
     *
     * @param string $package Package to set.
     * @return \Softworx\RocXolid\Contracts\Translatable
     */
    public function setTranslationPackage(string $package): Translatable;

    /**
     * Retrieve the translation package.
     * 
     * @return string
     * @throws \UnderflowException If no package is set.
     */
    public function getTranslationPackage(): string;

    /**
     * Check for translation package being set.
     * 
     * @return bool
     */
    public function hasTranslationPackage(): bool;

    /**
     * Set the language name.
     *
     * @param string $language_name Language name to set.
     * @return \Softworx\RocXolid\Contracts\Translatable
     */
    public function setLanguageName(string $language_name): Translatable;

    /**
     * Get the language name.
     *
     * @return string
     * @throws \UnderflowException If no language name is set.
     */
    public function getLanguageName(): string;

    /**
     * Check if the language name is set.
     *
     * @return bool
     */
    public function hasLanguageName(): bool;
}
