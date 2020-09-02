<?php

namespace Softworx\RocXolid\Triggers\Contracts;

use Illuminate\Support\Collection;
// rocXolid contracts
use Softworx\RocXolid\Contracts\TranslationPackageProvider;

/**
 * Interface for triggers - isolation of business logic that can be dynamically assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Trigger
{
    /**
     * Check if the trigger is assigned to any document.
     *
     * @param string $provider_type
     * @return bool
     */
    public function isAssignedToProvider(string $provider_type): bool;

    /**
     * Obtain providers that have the trigger assigned.
     *
     * @param string $provider_type
     * @return \Illuminate\Support\Collection
     */
    public function getAssignedProviders(string $provider_type): Collection;

    /**
     * Retrieve translated trigger title.
     *
     * @param \Softworx\RocXolid\Contracts\TranslationPackageProvider\TranslationPackageProvider $controller
     * @return string
     */
    public function getTranslatedTitle(TranslationPackageProvider $controller): string;

    /**
     * Priamry method to handle trigger's business logic.
     *
     * @param mixed ...$arguments
     * @return \Softworx\RocXolid\Triggers\Contracts;\Trigger
     */
    public function fire(...$arguments): Trigger;
}
