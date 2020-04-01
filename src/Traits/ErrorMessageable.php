<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Contracts\ErrorMessageable as ErrorMessageableContract;

/**
 * Enables object to have error messages assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ErrorMessageable
{
    /**
     * {@inheritdoc}
     */
    public function setErrorMessages(array $messages, ?int $index = null): ErrorMessageableContract
    {
        if (is_null($index)) {
            $this->error_messages = collect($messages);
        } else {
            $this->getErrorMessages()->put($index, collect($messages));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorMessage(): string
    {
        return $this->getErrorMessages()->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexErrorMessage(int $index): string
    {
        if ($this->getErrorMessages()->has($index)) {
            return $this->getErrorMessages()->get($index)->first();
        } elseif ($this->getErrorMessages()->isNotEmpty()) {
            throw new \OutOfRangeException(sprintf('Invalid index [%s] requested, available: %s', $index, implode(', ', $this->getErrorMessages()->keys()->all())));
        } else {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorMessages(): Collection
    {
        if (!isset($this->error_messages)) {
            $this->error_messages = collect();
        }

        return $this->error_messages;
    }

    /**
     * {@inheritdoc}
     */
    public function hasErrorMessages(int $index = null): bool
    {
        return is_null($index) ? $this->getErrorMessages()->isNotEmpty() : $this->getErrorMessages()->has($index);
    }
}
