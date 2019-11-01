<?php

namespace Softworx\RocXolid\Traits;

/**
 * Enables object to provide translation param.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait TranslationParamProvider
{
    /**
     * @var string $translation_param Identifier for param containing translations for object using this trait.
     */
    protected $translation_param = null; // should be defined in param specific class

    /**
     * {@inheritdoc}
     */
    public function provideTranslationParam(): string
    {
        if (!$this->issetTranslationParam()) {
            throw new \UnderflowException(sprintf('No translation param set in [%s]', get_class($this)));
        }

        return $this->translation_param;
    }

    public function issetTranslationParam(): bool
    {
        return isset($this->translation_param) && !empty($this->translation_param);
    }
}
