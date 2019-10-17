<?php

namespace Softworx\RocXolid\Services;

use Illuminate\Translation\Translator;
use Softworx\RocXolid\Services\Contracts\TranslationService as TranslationServiceContract;
use Softworx\RocXolid\Contracts\Translatable;

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

    protected function getTranslationKey($key, $use_repository_param): string
    {
        if (!$use_repository_param) {
            return sprintf('general.%s', $key);
        } elseif (method_exists($this, 'getRepository') && $this->getRepository()) {
            return sprintf('%s.%s', $this->getRepository()->getTranslationParam(), $key);
        } else {//if ($this->getController() && $this->getController()->getRepository())
            return '---component--- (' . __METHOD__ . ')';
        }

        return $key;
    }
}
