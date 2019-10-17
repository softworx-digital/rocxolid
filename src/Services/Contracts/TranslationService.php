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
     * @return string
     */
    public function getTranslation(Translatable $component, string $key): string;
}
