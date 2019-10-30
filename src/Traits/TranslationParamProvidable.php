<?php

namespace Softworx\RocXolid\Traits;

/**
 * Enables object to provide translation param.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait TranslationParamProvidable
{
    /**
     * @var string $translation_param Identifier for param containing translations for object using this trait.
     */
    protected static $translation_param = null; // should be defined in param specific class

    /**
     * {@inheritdoc}
     */
    public function provideTranslationParam(): string
    {
        if (!$this->issetTranslationParam()) {
            throw new \UnderflowException(sprintf('No translation param set in [%s]', get_class($this)));
        }

        return static::$translation_param;
    }

    public function issetTranslationParam(): bool
    {
        return isset(static::$translation_param) && !empty(static::$translation_param);
    }
}
