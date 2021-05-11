<?php

namespace Softworx\RocXolid\Models\Contracts;

// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Enables the model to be rated.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Rateable
{
    /**
     * Add new rating (by given rater).
     *
     * @param float $rating
     * @param \Softworx\RocXolid\Models\Contracts\Crudable|null $rater
     * @param array|null $rating_data
     * @return \Softworx\RocXolid\Models\Contracts\Rateable
     */
    public function addRating(float $rating, ?Crudable $rater = null, ?array $rating_data = null): Rateable;

    /**
     * Check if model is already rated (by rater).
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable|null $rater
     * @return boolean
     */
    public function isRated(?Crudable $rater = null): bool;

    /**
     * Obtain rating.
     *
     * @return float
     */
    public function getRating(): float;

    /**
     * Obtain rating count.
     *
     * @return integer
     */
    public function getRatingCount(): int;

    /**
     * Obtain rating attribute name.
     *
     * @return string
     */
    public function getRatingColumn(): string;

    /**
     * Obtain rating count attribute name.
     *
     * @return string
     */
    public function getRatingCountColumn(): string;
}
