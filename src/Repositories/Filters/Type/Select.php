<?php

namespace Softworx\RocXolid\Repositories\Filters\Type;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Repositories\Contracts\Filterable;
use Softworx\RocXolid\Repositories\Contracts\Filter;
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
use Softworx\RocXolid\Repositories\Filters\AbstractFilter;

class Select extends AbstractFilter
{
    protected $default_options = [
        'type-template' => 'select',
        // field wrapper
        'wrapper' => false,
        // field label
        'label' => false,
        // field HTML attributes
        'attributes' => [
            'class' => 'form-control',
            'data-live-search' => true,
        ],
    ];

    public function apply(Filterable $repository)
    {
        $query = $repository->getQuery();

        return $query->where($this->getColumnName($query), $this->getValue());
    }

    public function setOptionValues(array $option_values): Filter
    {
        foreach ($option_values as $value => &$title) {
            $title = $this->translate(sprintf('%s_options.%s', $this->getName(), $title));
        }

        $this->collection = collect($option_values);

        return $this;
    }

    public function getCollection(): Collection
    {
        $collection = $this->collection;
        $collection->prepend($this->getOption($this->translate('component.label.title')), '');

        return $collection;
    }
}
