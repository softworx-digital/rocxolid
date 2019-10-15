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
     * @var \Illuminate\Support\Collection $error_messages Assigned error messages collection.
     */
    private $error_messages;

    /**
     * {@inheritdoc}
     */
    public function setErrorMessage(string $message, int $index = 0): ErrorMessageableContract
    {
        $this->getErrorMessages()->put($index, $message);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setErrorMessages(array $messages): ErrorMessageableContract
    {
        $this->error_messages = new Collection($messages);

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
            return $this->getErrorMessages()->get($index);
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
            $this->error_messages = new Collection();
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
