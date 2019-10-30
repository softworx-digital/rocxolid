<?php

namespace Softworx\RocXolid\Repositories\Support;

use Softworx\RocXolid\Contracts\EventDispatchable;
use Softworx\RocXolid\Repositories\Contracts\RepositoryFilterBuilder as RepositoryFilterBuilderContract;
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Repositories\Contracts\Filter;
use Softworx\RocXolid\Repositories\Events\AfterFilterCreation;

class RepositoryFilterBuilder implements RepositoryFilterBuilderContract
{
    private static $required_filter_settings = [
        'type',
        'options',
    ];

    public function addDefinitionFilters(Repository $repository, $definition): RepositoryFilterBuilderContract
    {
        $filters = [];

        $this
            ->validateFiltersDefinition($definition)
            ->processFiltersDefinition($repository, $definition, $filters);

        $repository->setFilters($filters);

        return $this;
    }

    public function makeFilter(Repository $repository, $type, $name, array $options = []): Filter
    {
        $field = new $type($name, $type, $repository, $options);

        if ($repository instanceof EventDispatchable) {
            $repository->getEventDispatcher()->dispatch(new AfterFilterCreation($repository, $field));
        }

        return $field;
    }

    protected function processDefinition(Repository $repository, $definition, &$items, $name_prefix = null): RepositoryFilterBuilderContract
    {
        foreach ($definition as $name => $settings) {
            $type = null;
            $options = null;

            $this
                ->processFilterSettings($settings, $type, $options)
                ->processFilterName($name, $name_prefix)
                ->processFilterType($type)
                ->processFilterOptions($repository, $name, $type, $options);

            if (isset($filters[$name])) {
                throw new \InvalidArgumentException(sprintf('Filter [%s] is already set in repository fields', $name));
            }

            $items[$name] = $this->makeFilter($repository, $type, $name, $options);
        }

        return $this;
    }

    protected function validateFiltersDefinition($definition): RepositoryFilterBuilderContract
    {
        if (!isset($definition['filters'])) {
            throw new \InvalidArgumentException(sprintf('Repository fields not defined in definition [%s]', print_r($definition, true)));
        } elseif (!is_bool($definition['filters']) && !is_array($definition['filters'])) {
            throw new \InvalidArgumentException(sprintf('Invalid repository filters definition [%s], boolean or array expected', gettype($definition['filters'])));
        }

        return $this;
    }

    protected function processFiltersDefinition(Repository $repository, $definition, &$filters, $name_prefix = null): RepositoryFilterBuilderContract
    {
        $this->processDefinition($repository, $definition['filters'], $filters, $name_prefix);

        return $this;
    }

    protected function processFilterSettings(&$settings, &$type, &$options): RepositoryFilterBuilderContract
    {
        foreach (self::$required_filter_settings as $required) {
            if (!isset($settings[$required])) {
                throw new \InvalidArgumentException(sprintf('Required filter setting [%s] not found in settings: %s', $required, print_r($settings)));
            }
        }

        extract($settings); // $type, $options

        return $this;
    }

    protected function processFilterName(&$name, $name_prefix): RepositoryFilterBuilderContract
    {
        $name = is_null($name_prefix) ? $name : sprintf('%s-%s', $name_prefix, $name);

        if (!preg_match('/[\w-]+/', $name)) {
            throw new \InvalidArgumentException(sprintf('Invalid filter name [%s] given', $name));
        }

        return $this;
    }

    protected function processFilterType(&$type): RepositoryFilterBuilderContract
    {
        if (!class_exists($type)) {
            throw new \InvalidArgumentException(sprintf('Invalid filter type [%s] given', $type));
        }

        return $this;
    }

    protected function processFilterOptions(Repository $repository, $name, $type, &$options): RepositoryFilterBuilderContract
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
