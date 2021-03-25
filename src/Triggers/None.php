<?php

namespace Softworx\RocXolid\Triggers;

use Illuminate\Support\Collection;
// rocXolid contracts
use Softworx\RocXolid\Contracts\TranslationPackageProvider;
// rocXolid trigger contracts
use Softworx\RocXolid\Triggers\Contracts\Trigger;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\TriggersProvider;
// rocXolid triggers
use Softworx\RocXolid\Triggers\AbstractTrigger;

/**
 * No-action trigger.
 * Serves as a sample.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class None extends AbstractTrigger
{
    /**
     * {@inheritDoc}
     */
    public function isFireable(TriggersProvider $provider, ...$arguments): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function fire(...$arguments): Trigger
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function validateAssignmentData(Collection $data, string $attribute): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function assignmentValidationErrorMessage(TranslationPackageProvider $controller, Collection $data): string
    {
        return '';
    }
}
