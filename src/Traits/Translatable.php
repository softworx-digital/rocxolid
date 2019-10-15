<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Translation\Translator;

/**
 * Enables object to translate language keys.
 * @todo finish actual translation
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Translatable
{
    /**
     * @var string $language_name Language name reference.
     */
    protected $language_name;

    /**
     * {@inheritdoc}
     */
    public function setLanguageName(string $language_name)
    {
        $this->language_name = $language_name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguageName()
    {
        if (!$this->hasLanguageName()) {
            throw new \UnderflowException(sprintf('No language name set in [%s]', get_class($this)));
        }

        return $this->language_name;
    }

    /**
     * {@inheritdoc}
     */
    public function hasLanguageName(): bool
    {
        return isset($this->language_name);
    }

    /**
     * {@inheritdoc}
     */
    public function translate($key)
    {
        return $key;
    }
}
