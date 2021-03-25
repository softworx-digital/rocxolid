<?php

namespace Softworx\RocXolid\Contracts;

/**
 * Enables object to translate language keys.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface TranslationProvider
{
    /**
     * Translate the language key.
     *
     * @param string $key Translation key to translate.
     * @param bool $use_raw_key Flag to use raw or transform the key.
     * @return string
     */
    public function translate(string $key, array $params = [], bool $use_raw_key = false): string;
}
