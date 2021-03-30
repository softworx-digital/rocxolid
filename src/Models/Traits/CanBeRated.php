<?php

namespace Softworx\RocXolid\Models\Traits;

use Illuminate\Support\Facades\DB;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;
use Softworx\RocXolid\Models\Contracts\Rateable;

/**
 * Trait to satisfy that model can be rated.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait CanBeRated
{
    /**
     * Model attribute that stores rating value.
     *
     * @var string
     */
    protected static $rating_column = 'rating';

    /**
     * Model attribute that stores rating count value.
     *
     * @var string
     */
    protected static $rating_count_column = 'rating_count';

    /**
     * {@inheritDoc}
     */
    public function addRating(float $rating, ?Crudable $rater = null): Rateable
    {
        $current = $this->{static::$rating_column} * $this->{static::$rating_count_column};
        $this->{static::$rating_count_column}++;
        $this->{static::$rating_column} = ($current + $rating) / $this->{static::$rating_count_column};

        !$rater ?: DB::table('_ratings')->insert([
            'rater_model' => (new \ReflectionClass($rater))->getName(),
            'rater_id' => $rater->getKey(),
            'ratee_model' => (new \ReflectionClass($this))->getName(),
            'ratee_id' => $this->getKey(),
        ]);

        $this->save();

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isRated(?Crudable $rater = null): bool
    {
        return !$rater
            ? ($this->getRatingCount() > 0)
            : DB::table('_ratings')->where([
                'rater_model' => (new \ReflectionClass($rater))->getName(),
                'rater_id' => $rater->getKey(),
                'ratee_model' => (new \ReflectionClass($this))->getName(),
                'ratee_id' => $this->getKey(),
            ])->exists();
    }

    /**
     * {@inheritDoc}
     */
    public function getRating(): float
    {
        return $this->{static::$rating_column};
    }

    /**
     * {@inheritDoc}
     */
    public function getRatingCount(): int
    {
        return $this->{static::$rating_count_column};
    }

    /**
     * {@inheritDoc}
     */
    public function getRatingColumn(): string
    {
        return static::$rating_column;
    }

    /**
     * {@inheritDoc}
     */
    public function getRatingCountColumn(): string
    {
        return static::$rating_count_column;
    }
}
