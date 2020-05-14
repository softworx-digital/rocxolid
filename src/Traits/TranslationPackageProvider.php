<?php

namespace Softworx\RocXolid\Traits;

/**
 * Enables object to provide translation package.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait TranslationPackageProvider
{
    /**
     * @var string $translation_package Identifier for package containing translations for object using this trait.
     */
    protected $translation_package = null; // should be defined in package specific class

    /**
     * {@inheritdoc}
     */
    public function provideTranslationPackage(): string
    {
        if (!$this->issetTranslationPackage()) {
            $this->translation_package = $this->guessTranslationPackage();
        }

        if (!$this->issetTranslationPackage()) {
            throw new \UnderflowException(sprintf('No translation package set in [%s]', get_class($this)));
        }

        return $this->translation_package;
    }

    public function issetTranslationPackage(): bool
    {
        return isset($this->translation_package) && !empty($this->translation_package);
    }

    protected function guessTranslationPackage(): ?string
    {
        return null;
    }
}
