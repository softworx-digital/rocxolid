<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Illuminate\Support\Collection;
// rocXolid model scopes
use Softworx\RocXolid\Models\Scopes\Owned as OwnedScope;
// rocXolid filters
use Softworx\RocXolid\Filters\StartsWith;
// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\FormField;
// rocXolid form field types
use Softworx\RocXolid\Forms\Fields\Type\CollectionSelect;

class CollectionSelectAutocomplete extends CollectionSelect
{
    const LIMIT = 15;

    protected $default_options = [
        'type-template' => 'collection-select-autocomplete',
        // collection settings
        'collection' => [
        ],
        // autocompletion settings
        'autocomplete' => [
            'filters' => [
                StartsWith::class
            ],
        ],
        // field wrapper
        'wrapper' => false,
        // component helper classes
        'helper-classes' => [
            'error-class' => 'has-error',
            'success-class' => 'has-success',
        ],
        // field label
        'label' => false,
        // field HTML attributes
        'attributes' => [
            'placeholder' => null,
            'class' => 'form-control autocomplete',
            'data-live-search' => 'true',
        ],
    ];

    protected $autocomplete_filters;

    protected $autocomplete_columns;

    protected $collection_model = null;

    protected $collection_filters = [];

    public function autocomplete(string $search): Collection
    {
        !$this->autocomplete_columns ?: $this->collection_model->setSearchColumns($this->autocomplete_columns);

        $query = $this->collection_model->query();

        collect($this->autocomplete_filters)->each(function (string $type) use ($query, $search) {
            app($type)->apply($query, $this->collection_model, $search);
        });

        collect($this->collection_filters)->each(function (array $filter) use ($query) {
            app($filter['type'])->apply($query, $this->collection_model, $filter['data']);
        });

        return $query
            ->select($this->collection_model->qualifyColumn('*'))
            ->take(static::LIMIT)
            ->get();
    }

    public function setCollection($option)
    {
        $this->collection_model = $option['model']::make();
        $this->collection_filters = $option['filters'] ?? [];

        return $this;
    }

    public function getCollection()
    {
        if ($this->hasValue() && ($model = $this->collection_model->find($this->getValue()))) {
            // @todo don't know why getValue() returns collection (sometimes?)
            if ($model instanceof Collection) {
                $model = $model->first();
            }

            return collect([
                $model->getKey() => $model->getTitle()
            ]);
        }

        return collect();
    }

    public function setAutocomplete(array $settings): FormField
    {
        return $this
            ->setAutocompleteColumns($settings['columns'] ?? null)
            ->setAutocompleteFilters($settings['filters'] ?? null)
            ->setComponentOptions('attributes', [ 'data-abs-ajax-url' => $settings['url'] ?? $this->getAutocompleteUrl() ]);
    }

    public function addFilter($filter)
    {
        $this->collection_filters[] = $filter;

        return $this;
    }

    private function setAutocompleteColumns(?array $columns): FormField
    {
        $this->autocomplete_columns = $columns;

        return $this;
    }

    private function setAutocompleteFilters(?array $filters): FormField
    {
        $this->autocomplete_filters = $filters;

        return $this;
    }

    private function getAutocompleteUrl(): string
    {
        return $this->getForm()->getModel()->getControllerRoute('formFieldAutocomplete', [
            'param' => $this->getForm()->getParam(),
            'field' => $this->getName(),
        ]);
    }
}
