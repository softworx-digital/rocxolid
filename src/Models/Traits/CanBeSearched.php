<?php

namespace Softworx\RocXolid\Models\Traits;

use Illuminate\Support\Collection;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Searchable;

/**
 * Trait to satisfy that model can be searched for.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait CanBeSearched
{
    /**
     * Model columns that can be used for searching.
     * Naively assume the model has title column.
     * This can be overriden in specific model class.
     *
     * @var array
     */
    protected $search_columns = [ 'title' ];

    /**
     * {@inheritDoc}
     */
    public function getSearchColumns(): Collection
    {
        return collect($this->search_columns);
    }

    /**
     * {@inheritDoc}
     */
    public function setSearchColumns(array $search_columns): Searchable
    {
        $this->search_columns = $search_columns;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toSearchResult(?string $param = null): array
    {
        return [
            'value' => $this->getKey(),
            'text' => $this->getTitle(),
        ];
    }
}
