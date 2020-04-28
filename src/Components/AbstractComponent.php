<?php

namespace Softworx\RocXolid\Components;

// rocXolid renderables
use Softworx\RocXolid\Rendering\AbstractRenderable;
// rocXolid helpers
use Softworx\RocXolid\Helpers\View as ViewHelper;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Translatable;
use Softworx\RocXolid\Contracts\TranslationPackageProvider;
use Softworx\RocXolid\Contracts\TranslationParamProvider;
// rocXolid traits
use Softworx\RocXolid\Traits\Translatable as TranslatableTrait;

/**
 * Base abstract class for components.
 * Component is an item that can be shown on front end (rendered) with many utility functions around.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractComponent extends AbstractRenderable implements Translatable
{
    use TranslatableTrait;

    /**
     * @var string
     */
    protected $dom_id;

    /**
     * @var string
     */
    protected $view_package = 'rocXolid';

    public static function build(
        TranslationPackageProvider $translation_package_provider = null,
        TranslationParamProvider $translation_param_provider = null
    ) {
        $component = app(static::class);

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

    protected function buildSubComponent(string $class)
    {
        return $class::buildInside($this);
    }

    public function setDomId(string $id): AbstractComponent
    {
        $this->dom_id = $id;

        return $this;
    }

    public function getDomId(...$params): string
    {
        return $this->makeDomId(...$params);
    }

    public function getDomIdHash(...$params): string
    {
        return sprintf('#%s', $this->getDomId(...$params));
    }

    protected function makeDomId(...$params): string
    {
        return ViewHelper::domId($this, ...$params);
    }
}
