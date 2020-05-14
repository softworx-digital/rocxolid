<?php

namespace Softworx\RocXolid\Tables\Traits;

use Softworx\RocXolid\Tables\Contracts\Paginationable as PaginationableContract;

/**
 * Enables data tables to paginate results.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Paginationable
{
    /**
     * {@inheritDoc}
     * @todo: configurable for specific table
     */
    public function getPerPage(): int
    {
        return static::PAGE_LIMIT_DEFAULT;
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrentPage(): int
    {
        $key = $this->getSessionKey(static::PAGE_SESSION_PARAM);

        if ($this->getRequest()->has(static::PAGE_REQUEST_PARAM)) {
            $this->getRequest()->session()->put($key, $this->getRequest()->get(static::PAGE_REQUEST_PARAM));
        }

        return $this->getRequest()->session()->get($key, static::PAGE_FIRST);
    }

    /**
     * Reset pagination.
     *
     * @return \Softworx\RocXolid\Tables\Contracts\Paginationable
     */
    protected function resetPagination(): PaginationableContract
    {
        $this->getRequest()->session()->forget($this->getSessionKey(static::PAGE_SESSION_PARAM));

        return $this;
    }

    /**
     * Obtain paginator base path.
     *
     * @return string
     */
    protected function getPaginatorRoutePath(): string
    {
        return $this->getController()->getRoute('index');
    }
}
