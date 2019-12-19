<?php

namespace Softworx\RocXolid\Services\Contracts;

use Softworx\RocXolid\Contracts\Translatable;

/**
 * Retrieves translation for given object and language key.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface TranslationService
{
    /**
     * Translate given language key for given component.
     *
     * @param Translatable $component Component to get translation for.
     * @param string $key Translation key to translate.
     * @param array $params Translation string parameters bindings.
     * @param bool $use_raw_key Flag to use raw or transform the key by the Translatable.
     * @return string
     */
    public function getTranslation(Translatable $component, string $key, array $params = [], bool $use_raw_key = false): string;
}
