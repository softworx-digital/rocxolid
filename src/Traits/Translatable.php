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
    // protected $translation_package; // should be defined in package specific (Abstract)Component class

    /**
     * @var string $language_name Language name reference.
     */
    protected $language_name;

    /**
     * {@inheritdoc}
     */
    public function translate(string $key, bool $use_repository_param = true): string
    {
        return $this->getTranslationService()->getTranslation($this, $key, $use_repository_param);
    }

    /**
     * {@inheritdoc}
     */
    public function setTranslationPackage(string $package): TranslatableContract
    {
        $this->translation_package = $package;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslationPackage(): string
    {
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
        return !is_null($this->translation_package);
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

    /**
     * Retrieves the view service responsible for retrieving and composing the views.
     * @TODO: pass as dependency via class constructor (however to all classes using this trait - awkward)
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
