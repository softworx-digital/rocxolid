<?php

namespace Softworx\RocXolid\Models\Traits;

trait HasTitleColumn
{
    protected static $title_column = 'name';

    public function getTitleColumn()
    {
        return static::$title_column;
    }

    public function getTitle()
    {
        return $this->{static::$title_column};
    }
}
