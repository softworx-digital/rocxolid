<?php

namespace Softworx\RocXolid\Tables\Contracts;

/**
 * Enables data tables to paginate results.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Paginationable
{
    const PAGE_FIRST = 1;

    const PAGE_LIMIT_DEFAULT = 30;

    const PAGE_REQUEST_PARAM = 'page';

    const PAGE_SESSION_PARAM = 'page';

    /**
     * Retrieve the number of records to show on one page.
     *
     * @return integer
     */
    public function getPerPage(): int;

    /**
     * Obtain the current results page the user is on.
     * Check if the page is provided by request, if so, set it to the session for specific table.
     * If the current results page is stored in the session, return it, default page otherwise.
     *
     * @return int
     */
    public function getCurrentPage(): int;

    /**
     * Reset pagination.
     *
     * @return \Softworx\RocXolid\Tables\Contracts\Paginationable
     */
    public function resetPagination(): Paginationable;
}
