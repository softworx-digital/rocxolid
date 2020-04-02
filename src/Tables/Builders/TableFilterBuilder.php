<?php

namespace Softworx\RocXolid\Tables\Builders;

use Softworx\RocXolid\Tables\Contracts\Table;
use Softworx\RocXolid\Tables\Contracts\Filter;
use Softworx\RocXolid\Tables\Builders\Contracts\TableFilterBuilder as TableFilterBuilderContract;

class TableFilterBuilder implements TableFilterBuilderContract
{
    private static $required_filter_settings = [
        'type',
        'options',
    ];

    public function addDefinitionFilters(Table $table, $definition): TableFilterBuilderContract
    {
        $filters = [];

        $this
            ->validateFiltersDefinition($definition)
            ->processFiltersDefinition($table, $definition, $filters);

        $table->setFilters($filters);

        return $this;
    }

    public function makeFilter(Table $table, $type, $name, array $options = []): Filter
    {
        $field = new $type($name, $type, $table, $options);

        return $field;
    }

    protected function processDefinition(Table $table, $definition, &$items, $name_prefix = null): TableFilterBuilderContract
    {
        foreach ($definition as $name => $settings) {
            $type = null;
            $options = null;

            $this
                ->processFilterSettings($settings, $type, $options)
                ->processFilterName($name, $name_prefix)
                ->processFilterType($type)
                ->processFilterOptions($table, $name, $type, $options);

            if (isset($filters[$name])) {
                throw new \InvalidArgumentException(sprintf('Filter [%s] is already set in table fields', $name));
            }

            $items[$name] = $this->makeFilter($table, $type, $name, $options);
        }

        return $this;
    }

    protected function validateFiltersDefinition($definition): TableFilterBuilderContract
    {
        if (!isset($definition['filters'])) {
            throw new \InvalidArgumentException(sprintf('Table fields not defined in definition [%s]', print_r($definition, true)));
        } elseif (!is_bool($definition['filters']) && !is_array($definition['filters'])) {
            throw new \InvalidArgumentException(sprintf('Invalid table filters definition [%s], boolean or array expected', gettype($definition['filters'])));
        }

        return $this;
    }

    protected function processFiltersDefinition(Table $table, $definition, &$filters, $name_prefix = null): TableFilterBuilderContract
    {
        $this->processDefinition($table, $definition['filters'], $filters, $name_prefix);

        return $this;
    }

    protected function processFilterSettings(&$settings, &$type, &$options): TableFilterBuilderContract
    {
        foreach (self::$required_filter_settings as $required) {
            if (!isset($settings[$required])) {
                throw new \InvalidArgumentException(sprintf('Required filter setting [%s] not found in settings: %s', $required, print_r($settings)));
            }
        }

        extract($settings); // $type, $options

        return $this;
    }

    protected function processFilterName(&$name, $name_prefix): TableFilterBuilderContract
    {
        $name = is_null($name_prefix) ? $name : sprintf('%s-%s', $name_prefix, $name);

        if (!preg_match('/[\w-]+/', $name)) {
            throw new \InvalidArgumentException(sprintf('Invalid filter name [%s] given', $name));
        }

        return $this;
    }

    protected function processFilterType(&$type): TableFilterBuilderContract
    {
        if (!class_exists($type)) {
            throw new \InvalidArgumentException(sprintf('Invalid filter type [%s] given', $type));
        }

        return $this;
    }

    protected function processFilterOptions(Table $table, $name, $type, &$options): TableFilterBuilderContract
    {
        /*
        foreach ($options as $option => $value)
        {
            switch ($option)
            {
                case 'xxx':
                    $options['yyy'] = 'zzz';
                    break;
            }
        }
        */

        return $this;
    }
}
