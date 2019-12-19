<?php

namespace Softworx\RocXolid\Traits;

use App;
use Softworx\RocXolid\Services\TranslationService;
use Softworx\RocXolid\Contracts\Translatable as TranslatableContract;

/**
 * Enables object to translate language keys.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Translatable
{
    /**
     * @var string $translation_package Identifier for package containing translations for component using this trait.
     */
    protected $translation_package;

    /**
     * @var string $translation_param Identifier for package param(file) containing translations for component using this trait.
     */
    protected $translation_param;

    /**
     * @var string $language_name Language name reference.
     */
    protected $language_name;

    /**
     * {@inheritdoc}
     */
    public function translate(string $key, array $params = [], bool $use_raw_key = false): string
    {
        return $this->getTranslationService()->getTranslation($this, $key, $params, $use_raw_key);
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslationKey(string $key): string
    {
        return $key;
    }

    /**
     * {@inheritdoc}
     */
    public function setTranslationPackage(string $package): TranslatableContract
    {
        if (empty($package)) {
            throw new \InvalidArgumentException(sprintf('Empty translation package [%s]', get_class($this)));
        }

        $this->translation_package = $package;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslationPackage(): string
    {
        if (!$this->hasTranslationPackage()) {
            $this->translation_package = $this->guessTranslationPackage();
        }

        if (!$this->hasTranslationPackage()) {
            throw new \UnderflowException(sprintf('No translation package set in [%s]', get_class($this)));
        }

        return $this->translation_package;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTranslationPackage(): bool
    {
        return isset($this->translation_package) && !empty($this->translation_package);
    }

    /**
     * {@inheritdoc}
     */
    public function setTranslationParam(string $param): TranslatableContract
    {
        if (empty($param)) {
            throw new \InvalidArgumentException(sprintf('Empty translation param [%s]', get_class($this)));
        }

        $this->translation_param = $param;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslationParam(): string
    {
        if (!$this->hasTranslationParam()) {
            $this->translation_param = $this->guessTranslationParam();
        }

        if (!$this->hasTranslationParam()) {
            throw new \UnderflowException(sprintf('No translation param set in [%s]', get_class($this)));

        }

        return $this->translation_param;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTranslationParam(): bool
    {
        return isset($this->translation_param) && !empty($this->translation_param);
    }

    /**
     * {@inheritdoc}
     */
    public function setLanguageName(string $language_name): TranslatableContract
    {
        $this->language_name = $language_name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguageName(): string
    {
        if (!$this->hasLanguageName()) {
            throw new \UnderflowException(sprintf('No translation language name set in [%s]', get_class($this)));
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

    // todo
    protected function guessTranslationPackage()
    {
        $reflection = new \ReflectionClass($this);

        return null;
    }

    // todo
    protected function guessTranslationParam()
    {
        $reflection = new \ReflectionClass($this);

        return null;
    }

    /**
     * Retrieves the view service responsible for retrieving and composing the views.
     * @todo: pass as dependency via class constructor (however to all classes using this trait - awkward)
     *
     * @return \Softworx\RocXolid\Services\Contracts\TranslationService
     */
    protected function getTranslationService(): TranslationService
    {
        if (!property_exists($this, 'translation_service') || is_null($this->translation_service)) {
            $translation_service = App::make(TranslationService::class);

            if (property_exists($this, 'translation_service')) {
                $this->translation_service = $translation_service;
            }
        } elseif (property_exists($this, 'translation_service')) {
            $translation_service = $this->translation_service;
        }

        return $translation_service;
    }
}
