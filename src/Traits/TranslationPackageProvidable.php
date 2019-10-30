<?php

namespace Softworx\RocXolid\Traits;

/**
 * Enables object to provide translation package.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait TranslationPackageProvidable
{
    /**
     * @var string $translation_package Identifier for package containing translations for object using this trait.
     */
    protected static $translation_package = null; // should be defined in package specific class

    /**
     * {@inheritdoc}
     */
    public function provideTranslationPackage(): string
    {
        if (!$this->issetTranslationPackage()) {
            throw new \UnderflowException(sprintf('No translation package set in [%s]', get_class($this)));
        }

        return static::$translation_package;
    }

    public function issetTranslationPackage(): bool
    {
        return isset(static::$translation_package) && !empty(static::$translation_package);
    }
}
