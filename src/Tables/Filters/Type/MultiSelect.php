<?php

namespace Softworx\RocXolid\Tables\Filters\Type;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Filterable;
use Softworx\RocXolid\Tables\Filters\Contracts\Filter;
// rocXolid table filters
use Softworx\RocXolid\Tables\Filters\AbstractFilter;

class MultiSelect extends AbstractFilter
{
    protected $default_options = [
        'type-template' => 'select-multiple',
        // field wrapper
        'wrapper' => false,
        // reset button
        'reset-button' => true,
        // field HTML attributes
        'attributes' => [
            'class' => 'form-control',
            'multiple' => true,
            'data-live-search' => true,
        ],
    ];

    public function apply(EloquentBuilder $query): EloquentBuilder
    {
        return $query->whereIn($this->getColumnName($query), $this->getValue());
    }

    /**
     * {@inheritDoc}
     */
    public function getFieldName(): string
    {
        return sprintf('%s[%s][]', self::DATA_PARAM, $this->getName());
    }

    public function setCollection(Collection $collection): Filter
    {
        $this->collection = $collection;

        return $this;
    }

    /*
    commented out because of translations
    public function setOptionValues(array $option_values): Filter
    {
        foreach ($option_values as $value => &$title) {
            $title = $this->translate(sprintf('%s_options.%s', $this->getName(), $title));
        }

        $this->collection = collect($option_values);

        return $this;
    }
    */

    public function getCollection(): Collection
    {
        $collection = $this->collection;
        // $collection->prepend($this->getOption($this->translate('component.label.title')), '');
        // $collection->prepend($this->getOption('component.label.title'), '');

        return $collection;
    }
}
