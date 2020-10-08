<?php

namespace Softworx\RocXolid\Triggers;

use Illuminate\Support\Collection;
// rocXolid contracts
use Softworx\RocXolid\Contracts\TranslationPackageProvider;
// rocXolid trigger contracts
use Softworx\RocXolid\Triggers\Contracts\Trigger;
// rocXolid triggers
use Softworx\RocXolid\Triggers\AbstractTrigger;

/**
 * No-action trigger.
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
