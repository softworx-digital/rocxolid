<?php

namespace Softworx\RocXolid\Tables\Filters\Type;

// contracts
use Softworx\RocXolid\Tables\Contracts\Filterable;
//
use Softworx\RocXolid\Tables\Filters\AbstractFilter;

class Text extends AbstractFilter
{
    protected $default_options = [
        'type-template' => 'text',
        // field wrapper
        'wrapper' => false,
        // field label
        'label' => false,
        // field HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
    ];

    public function apply(Filterable $repository)
    {
        $query = $repository->getQuery();

        return $query->where($this->getColumnName($query), 'like', sprintf('%%%s%%', $this->getValue()));
    }
}
