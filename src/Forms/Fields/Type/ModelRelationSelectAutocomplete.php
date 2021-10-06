<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Illuminate\Support\Collection;
// rocXolid model scopes
use Softworx\RocXolid\Models\Scopes\Owned as OwnedScope;
// rocXolid filters
use Softworx\RocXolid\Filters\StartsWith;
// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\FormField;
// rocXolid form fields
use Softworx\RocXolid\Forms\Fields\AbstractFormField;

/**
 * @todo this works well for direct standard forms (eg. address > address form)
 * does not work in general for ex. User directly uses fields of Address form (and the city field is of this type)
 * Conflicts in Controllers (User Controller is used over needed Address Controller)
 */
class ModelRelationSelectAutocomplete extends AbstractFormField
{
    const LIMIT = 15;

    protected $default_options = [
        'type-template' => 'model-relation-select',
        // autocompletion settings
        'autocomplete' => [
            'filters' => [
                StartsWith::class
            ],
        ],
        // relation used for this field
        'relation' => null,
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
            // 'placeholder' => null,
            'class' => 'form-control autocomplete',
            'data-live-search' => 'true',
        ],
    ];

    protected $model_relation;

    protected $queried_model;

    protected $autocomplete_filters;

    protected $autocomplete_columns;

    protected $model_filters = [];

    public function autocomplete(string $search): Collection
    {
        !$this->autocomplete_columns ?: $this->queried_model->setSearchColumns($this->autocomplete_columns);

        $query = $this->queried_model->query();

        collect($this->autocomplete_filters)->each(function (string $type) use ($query, $search) {
            app($type)->apply($query, $this->queried_model, $search);
        });

        collect($this->model_filters)->each(function (array $filter) use ($query) {
            // @todo decide whether to use 'type' or 'class' and unify all field types
            app($filter['type'])->apply($query, $this->queried_model, $filter['data']);
        });

        return $query
            ->select($this->queried_model->qualifyColumn('*'))
            ->take(static::LIMIT)
            ->get();
    }

    public function setRelation(string $relation): FormField
    {
        $this->model_relation = $this->getForm()->getModel()->{$relation}();
        $this->queried_model = $this->model_relation->getRelated();
        $this->model_filters = $relation['filters'] ?? [];

        return $this;
    }

    public function setRelationFilters(array $filters): FormField
    {
        $this->model_filters = $filters ?? [];

        return $this;
    }

    public function setAutocomplete(array $settings): FormField
    {
        return $this
            ->setAutocompleteColumns($settings['columns'] ?? null)
            ->setAutocompleteFilters($settings['filters'] ?? null)
            ->setComponentOptions('attributes', [ 'data-abs-ajax-url' => $settings['url'] ?? $this->getAutocompleteUrl() ]);
    }

    public function getCollection(): Collection
    {
        if (is_null($this->getOption('force-value', false))) {
            return collect();
        }

        if ($this->hasValue() && ($model = $this->model_relation->getRelated()->find($this->getValue()))) {
            // @todo don't know why getValue() returns collection (sometimes?)
            if ($model instanceof Collection) {
                $model = $model->first();
            }

            // @todo hotfixed
            if (is_null($model)) {
                return collect();
            }

            return collect([
                $model->getKey() => $model->getTitle()
            ]);
        }

        return collect();
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
