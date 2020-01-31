<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use DB;
use Illuminate\Support\Collection;
// rocXolid model scopes
use Softworx\RocXolid\Models\Scopes\Owned as OwnedScope;
// rocXolid filters
use Softworx\RocXolid\Filters\StartsWith;
// rocXolid form field types
use Softworx\RocXolid\Forms\Fields\Type\CollectionSelect;

// @todo: refactor
class CollectionSelectAutocomplete extends CollectionSelect
{
    const LIMIT = 10;

    protected $collection_model = null;

    protected $collection_model_column = null;

    protected $collection_model_method = null;

    protected $collection_filters = [];

    protected $collection_loaded = false;

    protected $default_options = [
        'type-template' => 'collection-select-autocomplete',
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

    public function setCollection($option)
    {
        if ($option instanceof Collection) {
            $this->collection = $option;
            $this->collection_loaded = true;
        } else {
            $this->collection = collect();
            $this->collection_model = ($option['model'] instanceof Model) ? $option['model'] : new $option['model'];
            $this->collection_model_column = $option['column'];
            $this->collection_model_method = isset($option['method']) ? $option['method'] : null;

            if (isset($option['filters'])) {
                $this->collection_filters = $option['filters'];
            }
        }

        return $this;
    }

    public function getCollection()
    {
        if (!$this->collection_loaded && $this->shouldLoad()) {
            $query = $model = $this->collection_model;

            foreach ($this->collection_filters as $filter) {
                $query = (new $filter['class']())->apply($query, $model, $filter['data']);
            }

            $this->collection = $query
                // ->take(static::LIMIT)
                ->pluck(sprintf(
                    '%s.%s',
                    $this->collection_model->getTable(),
                    $this->collection_model_column
                ), sprintf(
                    '%s.id',
                    $this->collection_model->getTable()
                ));
        } else {
            $value = (($this->getValue() instanceof Collection) && $this->getValue()->isEmpty()) ? null : $this->getValue();

            $this->collection = $this->collection_model
                ->where(sprintf('%s.id', $this->collection_model->getTable()), $value)
                // ->take(static::LIMIT)
                ->pluck(sprintf(
                    '%s.%s',
                    $this->collection_model->getTable(),
                    $this->collection_model_column
                ),
                sprintf(
                    '%s.id',
                    $this->collection_model->getTable()
                ));
        }

        if (!is_null($this->collection_model_method) && method_exists($this->collection_model, $this->collection_model_method)) {
            $this->collection = $this->collection->map(function (&$item, $id) {
                return $this->collection_model
                    ->find($id)->{$this->collection_model_method}();
            });
        }

        return $this->collection;
    }

    public function shouldLoad()
    {
        foreach ($this->collection_filters as $filter) {
            if ($filter['class'] == StartsWith::class) {
                return true;
            }
        }

        return false;
    }

    public function addFilter($filter)
    {
        $this->collection_filters[] = $filter;

        return $this;
    }
}
