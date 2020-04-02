<?php

namespace Softworx\RocXolid\Tables\Builders;

use Softworx\RocXolid\Tables\Contracts\Table;
use Softworx\RocXolid\Tables\Contracts\Column;
use Softworx\RocXolid\Tables\Builders\Contracts\TableColumnBuilder as TableColumnBuilderContract;

class TableColumnBuilder implements TableColumnBuilderContract
{
    private static $required_column_settings = [
        'type',
        'options',
    ];

    public function addDefinitionColumns(Table $table, $definition): TableColumnBuilderContract
    {
        $columns = [];

        $this
            ->validateColumnsDefinition($definition)
            ->processColumnsDefinition($table, $definition, $columns);

        $table->setColumns($columns);

        return $this;
    }

    public function makeColumn(Table $table, $type, $name, array $options = []): Column
    {
        $field = new $type($name, $type, $table, $options);

        return $field;
    }

    protected function processDefinition(Table $table, $definition, &$items, $name_prefix = null): TableColumnBuilderContract
    {
        foreach ($definition as $name => $settings) {
            $type = null;
            $options = null;

            $this
                ->processSettings($settings, $type, $options)
                ->processName($name, $name_prefix)
                ->processType($type)
                ->processOptions($table, $name, $type, $options);

            if (isset($columns[$name])) {
                throw new \InvalidArgumentException(sprintf('Column [%s] is already set in table fields', $name));
            }

            $items[$name] = $this->makeColumn($table, $type, $name, $options);
        }

        return $this;
    }

    protected function validateColumnsDefinition($definition): TableColumnBuilderContract
    {
        if (!isset($definition['columns'])) {
            throw new \InvalidArgumentException(sprintf('Table fields not defined in definition [%s]', print_r($definition, true)));
        } elseif (!is_bool($definition['columns']) && !is_array($definition['columns'])) {
            throw new \InvalidArgumentException(sprintf('Invalid table columns definition [%s], boolean or array expected', gettype($definition['columns'])));
        }

        return $this;
    }

    protected function processColumnsDefinition(Table $table, $definition, &$columns, $name_prefix = null): TableColumnBuilderContract
    {
        $this->processDefinition($table, $definition['columns'], $columns, $name_prefix);

        return $this;
    }

    protected function processSettings(&$settings, &$type, &$options): TableColumnBuilderContract
    {
        foreach (self::$required_column_settings as $required) {
            if (!isset($settings[$required])) {
                throw new \InvalidArgumentException(sprintf('Required column setting [%s] not found in settings: %s', $required, print_r($settings)));
            }
        }

        extract($settings); // $type, $options

        return $this;
    }

    protected function processName(&$name, $name_prefix): TableColumnBuilderContract
    {
        $name = is_null($name_prefix) ? $name : sprintf('%s-%s', $name_prefix, $name);

        if (!preg_match('/[\w-]+/', $name)) {
            throw new \InvalidArgumentException(sprintf('Invalid column name [%s] given', $name));
        }

        return $this;
    }

    protected function processType(&$type): TableColumnBuilderContract
    {
        if (!class_exists($type)) {
            throw new \InvalidArgumentException(sprintf('Invalid column type [%s] given', $type));
        }

        return $this;
    }

    protected function processOptions(Table $table, $name, $type, &$options): TableColumnBuilderContract
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
