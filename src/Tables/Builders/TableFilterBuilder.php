<?php

namespace Softworx\RocXolid\Tables\Builders;

use Illuminate\Support\Collection;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Table;
use Softworx\RocXolid\Tables\Filters\Contracts\Filter;
// rocXolid table builder contracts
use Softworx\RocXolid\Tables\Builders\Contracts\TableFilterBuilder as TableFilterBuilderContract;

/**
 * Builds data table filter fields for with array based definition.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo identify builders' common methods and unify them in abstract class
 */
class TableFilterBuilder implements TableFilterBuilderContract
{
    private static $required_filter_settings = [
        'type',
        'options',
    ];

    /**
     * {@inheritDoc}
     */
    public function addDefinitionFilters(Table $table, array $definition): TableFilterBuilderContract
    {
        $filters = collect();

        $this
            ->validateFiltersDefinition($definition)
            ->processFiltersDefinition($table, $definition, $filters);

        $table->setFilters($filters);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function makeFilter(Table $table, string $type, string $name, array $options = []): Filter
    {
        $filter = (new $type($table, $name, $type, $options));
        $filter->setValue($table->getFilterValue($filter));

        return $filter;
    }

    protected function processDefinition(Table $table, array $definition, Collection &$items, ?string $name_prefix = null): TableFilterBuilderContract
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
                throw new \InvalidArgumentException(sprintf('Filter [%s] is already set in table [%s] fields', $name, get_class($table)));
            }

            $items->put($name, $this->makeFilter($table, $type, $name, $options));
        }

        return $this;
    }

    protected function validateFiltersDefinition(array $definition): TableFilterBuilderContract
    {
        if (!isset($definition['filters'])) {
            throw new \InvalidArgumentException(sprintf('Table fields not defined for [%s] in definition [%s]', get_class($this), print_r($definition, true)));
        } elseif (!is_bool($definition['filters']) && !is_array($definition['filters'])) {
            throw new \InvalidArgumentException(sprintf('Invalid table filters definition [%s] for [%s], boolean or array expected', gettype($definition['filters'], get_class($this))));
        }

        return $this;
    }

    protected function processFiltersDefinition(Table $table, array $definition, Collection &$filters, ?string $name_prefix = null): TableFilterBuilderContract
    {
        $this->processDefinition($table, $definition['filters'], $filters, $name_prefix);

        return $this;
    }

    protected function processFilterSettings(array &$settings, ?string &$type, ?array &$options): TableFilterBuilderContract
    {
        foreach (self::$required_filter_settings as $required) {
            if (!isset($settings[$required])) {
                throw new \InvalidArgumentException(sprintf('Required filter setting [%s] not found for [%s] in settings: %s', $required, get_class($this), print_r($settings)));
            }
        }

        extract($settings); // $type, $options

        return $this;
    }

    protected function processFilterName(string &$name, ?string $name_prefix): TableFilterBuilderContract
    {
        $name = is_null($name_prefix) ? $name : sprintf('%s-%s', $name_prefix, $name);

        if (!preg_match('/[\w-]+/', $name)) {
            throw new \InvalidArgumentException(sprintf('Invalid filter name [%s] given for [%s]', $name, get_class($this)));
        }

        return $this;
    }

    protected function processFilterType(string &$type): TableFilterBuilderContract
    {
        if (!class_exists($type)) {
            throw new \InvalidArgumentException(sprintf('Invalid filter type [%s] given for [%s]', $type, get_class($this)));
        }

        return $this;
    }

    protected function processFilterOptions(Table $table, string $name, string $type, array &$options): TableFilterBuilderContract
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
