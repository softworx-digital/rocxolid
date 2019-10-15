<?php

namespace Softworx\RocXolid\Repositories\Support;

use Softworx\RocXolid\Contracts\EventDispatchable;
use Softworx\RocXolid\Repositories\Contracts\RepositoryColumnBuilder as RepositoryColumnBuilderContract;
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Repositories\Contracts\Column;
use Softworx\RocXolid\Repositories\Events\AfterColumnCreation;

class RepositoryColumnBuilder implements RepositoryColumnBuilderContract
{
    private static $required_column_settings = [
        'type',
        'options',
    ];

    public function addDefinitionColumns(Repository $repository, $definition): RepositoryColumnBuilderContract
    {
        $columns = [];

        $this
            ->validateColumnsDefinition($definition)
            ->processColumnsDefinition($repository, $definition, $columns);

        $repository->setColumns($columns);

        return $this;
    }

    public function addDefinitionButtons(Repository $repository, $definition): RepositoryColumnBuilderContract
    {
        $buttons = [];

        $this
            ->validateButtonsDefinition($definition)
            ->processButtonsDefinition($repository, $definition, $buttons);

        $repository->setButtons($buttons);

        return $this;
    }

    public function makeColumn(Repository $repository, $type, $name, array $options = []): Column
    {
        $field = new $type($name, $type, $repository, $options);

        if ($repository instanceof EventDispatchable) {
            $repository->getEventDispatcher()->fire(new AfterColumnCreation($repository, $field));
        }

        return $field;
    }

    protected function processDefinition(Repository $repository, $definition, &$items, $name_prefix = null): RepositoryColumnBuilderContract
    {
        foreach ($definition as $name => $settings) {
            $type = null;
            $options = null;

            $this
                ->processSettings($settings, $type, $options)
                ->processName($name, $name_prefix)
                ->processType($type)
                ->processOptions($repository, $name, $type, $options);

            if (isset($columns[$name])) {
                throw new \InvalidArgumentException(sprintf('Column [%s] is already set in repository fields', $name));
            }

            $items[$name] = $this->makeColumn($repository, $type, $name, $options);
        }

        return $this;
    }

    protected function validateColumnsDefinition($definition): RepositoryColumnBuilderContract
    {
        if (!isset($definition['columns'])) {
            throw new \InvalidArgumentException(sprintf('Repository fields not defined in definition [%s]', print_r($definition, true)));
        } elseif (!is_bool($definition['columns']) && !is_array($definition['columns'])) {
            throw new \InvalidArgumentException(sprintf('Invalid repository columns definition [%s], boolean or array expected', gettype($definition['columns'])));
        }

        return $this;
    }

    protected function processColumnsDefinition(Repository $repository, $definition, &$columns, $name_prefix = null): RepositoryColumnBuilderContract
    {
        $this->processDefinition($repository, $definition['columns'], $columns, $name_prefix);

        return $this;
    }

    protected function validateButtonsDefinition($definition): RepositoryColumnBuilderContract
    {
        if (!isset($definition['buttons'])) {
            throw new \InvalidArgumentException(sprintf('Buttons not defined in definition [%s]', print_r($definition, true)));
        } elseif (!is_bool($definition['buttons']) && !is_array($definition['buttons'])) {
            throw new \InvalidArgumentException(sprintf('Invalid buttons definition [%s], boolean or array expected', gettype($definition['buttons'])));
        }

        return $this;
    }

    protected function processButtonsDefinition(Repository $repository, $definition, &$buttons, $name_prefix = null): RepositoryColumnBuilderContract
    {
        $this->processDefinition($repository, $definition['buttons'], $buttons, $name_prefix);

        return $this;
    }

    protected function processSettings(&$settings, &$type, &$options): RepositoryColumnBuilderContract
    {
        foreach (self::$required_column_settings as $required) {
            if (!isset($settings[$required])) {
                throw new \InvalidArgumentException(sprintf('Required column setting [%s] not found in settings: %s', $required, print_r($settings)));
            }
        }

        extract($settings); // $type, $options

        return $this;
    }

    protected function processName(&$name, $name_prefix): RepositoryColumnBuilderContract
    {
        $name = is_null($name_prefix) ? $name : sprintf('%s-%s', $name_prefix, $name);

        if (!preg_match('/[\w-]+/', $name)) {
            throw new \InvalidArgumentException(sprintf('Invalid column name [%s] given', $name));
        }

        return $this;
    }

    protected function processType(&$type): RepositoryColumnBuilderContract
    {
        if (!class_exists($type)) {
            throw new \InvalidArgumentException(sprintf('Invalid column type [%s] given', $type));
        }

        return $this;
    }

    protected function processOptions(Repository $repository, $name, $type, &$options): RepositoryColumnBuilderContract
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
