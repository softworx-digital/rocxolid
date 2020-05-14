<?php

namespace Softworx\RocXolid\Models\Contracts;

interface AutocompleteSearchable
{
    public function getSearchColumn();

    public function getSearchId();

    public function getSearchValue();
}
