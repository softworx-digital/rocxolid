<?php

namespace Softworx\RocXolid\Models\Traits;

trait HasTitleColumn
{
    protected static $_title_column = 'name';

    public function getTitleColumn()
    {
        if (property_exists($this, 'title_column')) {
            return static::$title_column;
        } else {
            return static::$_title_column;
        }
    }

    public function getTitle(): string
    {
        if (is_array($this->getTitleColumn())) {
            return implode(' ', array_filter(array_map(function ($attribute) {
                return $this->{$attribute};
            }, $this->getTitleColumn())));
        }

        return  $this->{$this->getTitleColumn()};
    }
}
