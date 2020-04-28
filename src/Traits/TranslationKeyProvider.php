<?php

namespace Softworx\RocXolid\Traits;

/**
 * Enables object to provide translation key.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait TranslationKeyProvider
{
    /**
     * @var string $translation_key Identifier for key containing translations for object using this trait.
     */
    protected $translation_key = null; // should be defined in key specific class

    /**
     * {@inheritdoc}
     */
    public function provideTranslationKey(): string
    {
        if (!$this->issetTranslationKey()) {
            $this->translation_key = $this->guessTranslationKey();
        }

        if (!$this->issetTranslationKey()) {
            throw new \UnderflowException(sprintf('No translation key set in [%s]', get_class($this)));
        }

        return $this->translation_key;
    }

    public function issetTranslationKey(): bool
    {
        return isset($this->translation_key) && !empty($this->translation_key);
    }

    protected function guessTranslationKey(): ?string
    {
        return null;
    }
}
