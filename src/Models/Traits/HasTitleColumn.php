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
        if (is_array($this->getTitleColumn())) {
            return implode(' ', array_filter(array_map(function($attribute) {
                return $this->{$attribute};
            }, static::$title_column)));
        }

        return  $this->{static::$title_column};
    }
}
