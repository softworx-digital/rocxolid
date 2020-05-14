<?php

namespace Softworx\RocXolid\Repositories\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Enables repository data results to be paginated.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Paginationable
{
    /**
     * {@inheritDoc}
     */
    public function paginate(int $page, int $per_page, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->getCollectionQuery()->paginate($per_page, $columns, 'page', $page);
    }
}
