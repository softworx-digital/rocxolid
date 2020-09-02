<?php

namespace Softworx\RocXolid\Triggers;

use Illuminate\Support\Collection;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Controllable;
use Softworx\RocXolid\Contracts\TranslationPackageProvider;
use Softworx\RocXolid\Contracts\TranslationDiscoveryProvider;
use Softworx\RocXolid\Contracts\TranslationProvider;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\TriggersProvider;
// rocXolid traits
use Softworx\RocXolid\Traits as Traits;
// rocXolid components
use Softworx\RocXolid\Components\General\Message;
// rocXolid trigger contracts
use Softworx\RocXolid\Triggers\Contracts\Trigger;

/**
 * Abstract elementable dependency.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractTrigger implements Trigger, Controllable, TranslationDiscoveryProvider, TranslationProvider
{
    use Traits\Controllable;
    use Traits\TranslationPackageProvider;
    use Traits\TranslationParamProvider;
    use Traits\TranslationKeyProvider;

    /**
     * {@inheritDoc}
     */
    public function isAssignedToProvider(string $provider_type): bool
    {
        return $this->getAssignedProviders($provider_type)->isNotEmpty();
    }

    /**
     * {@inheritDoc}
     */
    public function getAssignedProviders(string $provider_type): Collection
    {
         return $provider_type::all()->filter(function (TriggersProvider $provider) {
             return $provider->provideTriggers()->filter(function (Trigger $trigger) {
                return ($trigger instanceof $this);
             })->isNotEmpty();
         });
    }

    /**
     * {@inheritDoc}
     */
    public function getTranslatedTitle(TranslationPackageProvider $controller): string
    {
        return $this->setController($controller)->translate(sprintf('trigger.%s.title', $this->provideTranslationKey()));
    }

    /**
     * {@inheritDoc}
     */
    public function translate(string $key, array $params = [], bool $use_raw_key = false): string
    {
        return Message::build($this, $this->getController())->translate($key, $params, $use_raw_key);
    }

    /**
     * {@inheritDoc}
     */
    protected function guessTranslationParam(): ?string
    {
        if ($this->hasController()) {
            throw new \RuntimeException(sprintf('No controller set for [%s]', get_class($this)));
        }

        return $this->getController()->provideTranslationParam();
    }
}
