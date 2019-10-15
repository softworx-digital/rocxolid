<?php

namespace Softworx\RocXolid\Repositories\Filters\Type;

// contracts
use Softworx\RocXolid\Repositories\Contracts\Filterable;
//
use Softworx\RocXolid\Repositories\Filters\AbstractFilter;

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
