<?php

namespace Softworx\RocXolid\Models\Contracts;

use Illuminate\Support\Collection;

/**
 * Enables model to be searched for.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Searchable
{
    /**
     * Obtain columns that can be searched.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSearchColumns(): Collection;

    /**
     * Dynamically set columns that can be searched.
     *
     * @param array $search_columns
     * @return \Softworx\RocXolid\Models\Contracts\Searchable
     */
    public function setSearchColumns(array $search_columns): Searchable;

    /**
     * Obtain structure that can be used as a search result.
     *
     * @return array
     */
    public function toSearchResult(?string $param = null): array;
}
