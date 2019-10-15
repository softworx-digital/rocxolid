<?php

namespace Softworx\RocXolid\Repositories\Traits;

use Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait Paginationable
{
    //private $_page_limit = 20;
    private $_page_limit = 30;

    private $_current_page = 1;

    private $_paginator = null;

    public function getPageLimit()
    {
        return $this->_page_limit;
    }

    public function getCurrentPage()
    {
        return $this->_current_page;
    }

    public function getPaginator()
    {
        if (is_null($this->_paginator)) {
            $this->paginate($this->getPageLimit());
        }

        return $this->adjustPaginator($this->_paginator);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate($per_page = 1, array $columns = ['*']): LengthAwarePaginator
    {
        $this
            ->getQuery();
        $this
            ->applyOrder()
            ->applyFilters()
            ->applyIntenalFilters();

        $param = md5(get_class($this)) . '_page';

        $session = Request::session();

        if (Request::has('page')) {
            $session->put($param, Request::input('page'));

            $page = Request::input('page');
        } elseif ($session->has($param)) {
            $page = $session->get($param);
        } else {
            $page = 1;
        }

        $this->_paginator = $this->query->paginate($per_page, $columns, 'page', $page)->withPath($this->getPaginatorPath());

        return $this->_paginator;
    }

    public function getPaginatorPath()
    {
        return $this->getController()->getRoute('index');
    }

    public function adjustPaginator(LengthAwarePaginator $paginator)
    {
        return $paginator;
    }
}
