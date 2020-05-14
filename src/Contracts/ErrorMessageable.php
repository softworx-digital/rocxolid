<?php

namespace Softworx\RocXolid\Contracts;

use Illuminate\Support\Collection;

/**
 * Enables object to have error messages assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface ErrorMessageable
{
    /**
     * Set error messages. Previously set messages will be overwritten.
     *
     * @param array $message Error messages to set.
     * @param int|null $index Index of ErrorMessageable to set error messages to.
     * @return \SoftSoftworx\RocXolid\Contracts\ErrorMessageable
     */
    public function setErrorMessages(array $messages, ?int $index = null): ErrorMessageable;

    /**
     * Get the first error message.
     *
     * @return string|null
     */
    public function getErrorMessage(): string;

    /**
     * Get the error message at given index.
     *
     * @return string|null
     * @throws \OutOfRangeException When the index is not accessible (undefined).
     */
    public function getIndexErrorMessage(int $index): string;

    /**
     * Get all error messages.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getErrorMessages(): Collection;

    /**
     * Check if there's an error message (at given index, if given).
     *
     * @return bool
     */
    public function hasErrorMessages(int $index = null): bool;
}
