<?php

namespace Softworx\RocXolid\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Enables repository data results to be paginated.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Paginationable
{
    /**
     * Retrieve results page.
     *
     * @param int $page
     * @param int $per_page
     * @param array $columns
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(int $page, int $per_page, array $columns = ['*']): LengthAwarePaginator;
}
