<?php

namespace Softworx\RocXolid\Models\Traits;

trait AutocompleteSearchable
{
    protected static $search_column = 'name';

    protected static $search_id_column = 'id';

    protected static $search_value_column = 'name';

    protected $query_string = '';

    public function getSearchColumn()
    {
        return static::$search_column;
    }

    public function getSearchId()
    {
        return $this->{static::$search_id_column};
    }

    public function getSearchValue()
    {
        return $this->{static::$search_value_column};
    }

    public function setQueryString($query_string)
    {
        $this->query_string = $query_string;

        return $this;
    }

    public function getQueryString()
    {
        return $this->query_string;
    }
}
