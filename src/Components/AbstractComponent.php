<?php

namespace Softworx\RocXolid\Components;

use Softworx\RocXolid\Helpers\View as ViewHelper;
use Softworx\RocXolid\Contracts\Translatable;
use Softworx\RocXolid\Contracts\Renderable;
use Softworx\RocXolid\Contracts\TranslationPackageProvider;
use Softworx\RocXolid\Contracts\TranslationParamProvider;
use Softworx\RocXolid\Traits\Renderable as RenderableTrait;
use Softworx\RocXolid\Traits\Translatable as TranslatableTrait;

/**
 * Base abstract class for components.
 * Component is an item that can be shown on front end (rendered) with many utility functions around.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractComponent implements Renderable, Translatable
{
    use RenderableTrait;
    use TranslatableTrait;

    const DEFAULT_TEMPLATE_NAME = 'default';

    /**
     * @var string
     */
    protected $dom_id;

    /**
     * @var string
     */
    protected $view_package = 'rocXolid';

    /**
     * @var string
     */
    protected $view_directory = '';

    public static function build(
        TranslationPackageProvider $translation_package_provider = null,
        TranslationParamProvider $translation_param_provider = null
    )
    {
        $component = new static();

        if (!is_null($translation_package_provider)) {
            $component->setTranslationPackage($translation_package_provider->provideTranslationPackage());
        }

        if (!is_null($translation_param_provider)) {
            $component->setTranslationParam($translation_param_provider->provideTranslationParam());
        }

        return $component;
    }

    public static function buildInside(Translatable $component)
    {
        return static::build()
            ->setTranslationPackage($component->getTranslationPackage())
            ->setTranslationParam($component->getTranslationParam());
    }

    public function setDomId(string $id): AbstractComponent
    {
        $this->dom_id = $id;

        return $this;
    }

    public function getDomId(...$params): string
    {
        if (!isset($this->dom_id)) {
            $this->setDomId($this->makeDomId(...$params));
        }

        return $this->dom_id;
    }

    protected function getDomIdHash(...$params): string
    {
        return ViewHelper::domIdHash($this, ...$params);
    }

    protected function makeDomId(...$params): string
    {
        return ViewHelper::domId($this, ...$params);
    }

    protected function makeDomIdHash(...$params): string
    {
        return ViewHelper::domIdHash($this, ...$params);
    }

    protected function buildSubComponent(string $class)
    {
        return $class::buildInside($this);
    }
}
