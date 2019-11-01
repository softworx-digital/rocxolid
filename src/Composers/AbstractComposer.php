<?php

namespace Softworx\RocXolid\Composers;

use Illuminate\Contracts\View\View;
use Softworx\RocXolid\Contracts\TranslationPackageProvider;
use Softworx\RocXolid\Contracts\TranslationParamProvider;
use Softworx\RocXolid\Composers\Contracts\Composer;
use Softworx\RocXolid\Traits\TranslationPackageProvider as TranslationPackageProviderTrait;
use Softworx\RocXolid\Traits\TranslationParamProvider as TranslationParamProviderTrait;

/**
 * Abstract composer to enable translation param configuration in specific subclasses.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractComposer implements Composer, TranslationPackageProvider, TranslationParamProvider
{
    use TranslationPackageProviderTrait;
    use TranslationParamProviderTrait;

    /**
     * {@inheritdoc}
     */
    public function compose(View $view): Composer
    {
        return $this;
    }
}
