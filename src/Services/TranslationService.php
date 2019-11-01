<?php

namespace Softworx\RocXolid\Services;

use Lang;
use Illuminate\Translation\Translator;
use Softworx\RocXolid\Services\Contracts\TranslationService as TranslationServiceContract;
use Softworx\RocXolid\Contracts\Translatable;

/**
 * Handles static texts translation.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid\Admin
 * @version 1.0.0
 */
class TranslationService implements TranslationServiceContract
{
    protected $cache = [];

    /**
     * {@inheritdoc}
     */
    public function getTranslation(Translatable $translatable, string $key): string
    {
        $language_key = $this->getTranslationKey(
            $translatable->getTranslationPackage(),
            $translatable->getTranslationParam(),
            $translatable->getTranslationKey($key)
        );

        $general_language_key = $this->getTranslationKey(
            $translatable->getTranslationPackage(),
            'general',
            $translatable->getTranslationKey($key)
        );

        if (Lang::has($language_key)) {
            return Lang::get($language_key);
        }

        return Lang::get($general_language_key);
    }

    /**
     * Create final translation key based on package, param and translation key.
     * 
     * @param string $package
     * @param string $param
     * @param string $key
     * @return string
     */
    protected function getTranslationKey(string $package, string $param, string $key): string
    {
        return sprintf('%s::%s.%s', $package, $param, $key);
    }
}
