<?php

namespace Softworx\RocXolid\Tables\Traits;

use Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait Paginationable
{
    // @todo: configurable
    //private $_page_limit = 20;
    private $page_limit = 30;

    public function getPerPage()
    {
        return $this->page_limit;
    }

    public function getCurrentPage()
    {
        $param = $this->getPaginatorSessionParam();

        if ($this->getRequest()->has(static::PAGE_REQUEST_PARAM)) {
            $this->getRequest()->session()->put($param, $this->getRequest()->get(static::PAGE_REQUEST_PARAM));
        }

        return $this->getRequest()->session()->get($param, 1);
    }

    /**
     * Retrieve results page.
     *
     * @param int $page
     * @param int $per_page
     * @param array $columns
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($per_page = 1, array $columns = ['*']): LengthAwarePaginator
    {
        return $this
            ->getController()
                ->getRepository()
                    ->paginate($this->getCurrentPage(), $per_page, $columns)
                    ->withPath($this->getPaginatorPath());
    }

    protected function getPaginatorPath()
    {
        return $this->getRoute('index');
    }

    protected function getPaginatorSessionParam()
    {
        return sprintf('%s-%s-page', md5(get_class($this)), $this->getParam());
    }
}
