<?php

namespace Softworx\RocXolid\Contracts;

/**
 * Enables object to fully handle translation with provided translation package and param.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Translatable extends TranslationProvider
{
    /**
     * Return the key to be used for translation.
     *
     * @return string
     */
    public function getTranslationKey(string $key): string;

    /**
     * Set the package responsible for translation.
     *
     * @param string $package Package to set.
     * @return \Softworx\RocXolid\Contracts\Translatable
     * @throws \InvalidArgumentException On empty package setting attempt.
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
     * Set the param (file) used for translation.
     *
     * @param string $param Param to set.
     * @return \Softworx\RocXolid\Contracts\Translatable
     * @throws \InvalidArgumentException On empty param setting attempt.
     */
    public function setTranslationParam(string $param): Translatable;

    /**
     * Retrieve the translation param (file).
     *
     * @return string
     * @throws \UnderflowException If no param is set.
     */
    public function getTranslationParam(): string;

    /**
     * Check for translation param (file) being set.
     *
     * @return bool
     */
    public function hasTranslationParam(): bool;

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
