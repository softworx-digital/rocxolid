<?php

namespace Softworx\RocXolid\Services;

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
    public function getTranslation(Translatable $component, string $key, bool $use_repository_param = true): string
    {
        return __(sprintf('%s::%s', $component->getTranslationPackage(), $this->getTranslationKey($key, $use_repository_param)));
    }

    /**
     * Return the translation key based on component.
     * 
     * @param string $key
     * @param bool $use_repository_param
     * @return string
     */
    protected function getTranslationKey(string $key, bool $use_repository_param): string
    {
        if (!$use_repository_param) {
            return sprintf('general.%s', $key);
        } elseif (method_exists($this, 'getRepository') && $this->getRepository()) {
            return sprintf('%s.%s', $this->getRepository()->getTranslationParam(), $key);
        } else {//if ($this->getController() && $this->getController()->getRepository())
            return $key . '---component--- (' . __METHOD__ . ')';
        }

        return $key;
    }
}
