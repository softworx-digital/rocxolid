<?php

namespace Softworx\RocXolid\Components\Tables;

use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Components\AbstractOptionableComponent;
use Softworx\RocXolid\Components\Contracts\TableFilterable as ComponentTableFilterable;
use Softworx\RocXolid\Repositories\Contracts\Filter;

class TableFilter extends AbstractOptionableComponent implements ComponentTableFilterable
{
    const ARRAY_TEMPLATE_NAME = 'array';

    protected $table_filter;

    public function setTableFilter(Filter $table_filter): ComponentTableFilterable
    {
        $this->table_filter = $table_filter;

        $this->setOptions($this->table_filter->getOption('component'));

        if ($placeholder = $this->getOption('attributes.placeholder', false)) {
            $this->mergeOptions([
                'attributes' => [
                    'placeholder' => $this->translate($placeholder)
                ]
            ]);
        }

        return $this;
    }

    public function getTableFilter(): Filter
    {
        if (is_null($this->table_filter)) {
            throw new \RuntimeException(sprintf('Table filter is not set yet to [%s] component', get_class($this)));
        }

        return $this->table_filter;
    }

    public function getDefaultTemplateName(): string
    {
        return $this->getTableFilter()->isArray()
             ? static::ARRAY_TEMPLATE_NAME
             : parent::getDefaultTemplateName();
    }

    // zmenit na value z requestu alebo session
    public function getModelValue()
    {
        if (is_null($this->getOption('model', null))) {
            throw new \RuntimeException(sprintf('Model is not set yet to [%s] component', get_class($this)));
        }

        $attribute = $this->getTableFilter()->getName();

        return $this->getOption('model')->$attribute;
    }

    public function getTranslationKey(string $key): string
    {
        /*
        if (!$use_repository_param) {
            return sprintf('filter.%s', $key);
        } elseif ($this->getTableFilter() && $this->getTableFilter()->getRepository()) {
            return sprintf('%s.filter.%s', $this->getTableFilter()->getRepository()->getTranslationParam(), $key);
        } else {//if ($this->getController() && $this->getController()->getRepository())
            return '---table-filter--- (' . __METHOD__ . ')';
        }
        */
        return '---table-filter--- (' . __METHOD__ . ')';

        return $key;
    }
}
