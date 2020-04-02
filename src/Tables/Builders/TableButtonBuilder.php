<?php

namespace Softworx\RocXolid\Tables\Builders;

use Softworx\RocXolid\Tables\Contracts\Table;
use Softworx\RocXolid\Tables\Contracts\Button;
use Softworx\RocXolid\Tables\Builders\Contracts\TableButtonBuilder as TableButtonBuilderContract;

class TableButtonBuilder implements TableButtonBuilderContract
{
    private static $required_button_settings = [
        'type',
        'options',
    ];

    public function addDefinitionButtons(Table $table, $definition): TableButtonBuilderContract
    {
        $buttons = [];

        $this
            ->validateButtonsDefinition($definition)
            ->processButtonsDefinition($table, $definition, $buttons);

        $table->setButtons($buttons);

        return $this;
    }

    public function makeButton(Table $table, $type, $name, array $options = []): Button
    {
        $field = new $type($name, $type, $table, $options);

        return $field;
    }

    protected function processDefinition(Table $table, $definition, &$items, $name_prefix = null): TableButtonBuilderContract
    {
        foreach ($definition as $name => $settings) {
            $type = null;
            $options = null;

            $this
                ->processSettings($settings, $type, $options)
                ->processName($name, $name_prefix)
                ->processType($type)
                ->processOptions($table, $name, $type, $options);

            if (isset($buttons[$name])) {
                throw new \InvalidArgumentException(sprintf('Button [%s] is already set in table fields', $name));
            }

            $items[$name] = $this->makeButton($table, $type, $name, $options);
        }

        return $this;
    }

    protected function validateButtonsDefinition($definition): TableButtonBuilderContract
    {
        if (!isset($definition['buttons'])) {
            throw new \InvalidArgumentException(sprintf('Buttons not defined in definition [%s]', print_r($definition, true)));
        } elseif (!is_bool($definition['buttons']) && !is_array($definition['buttons'])) {
            throw new \InvalidArgumentException(sprintf('Invalid buttons definition [%s], boolean or array expected', gettype($definition['buttons'])));
        }

        return $this;
    }

    protected function processButtonsDefinition(Table $table, $definition, &$buttons, $name_prefix = null): TableButtonBuilderContract
    {
        $this->processDefinition($table, $definition['buttons'], $buttons, $name_prefix);

        return $this;
    }

    protected function processSettings(&$settings, &$type, &$options): TableButtonBuilderContract
    {
        foreach (self::$required_button_settings as $required) {
            if (!isset($settings[$required])) {
                throw new \InvalidArgumentException(sprintf('Required button setting [%s] not found in settings: %s', $required, print_r($settings)));
            }
        }

        extract($settings); // $type, $options

        return $this;
    }

    protected function processName(&$name, $name_prefix): TableButtonBuilderContract
    {
        $name = is_null($name_prefix) ? $name : sprintf('%s-%s', $name_prefix, $name);

        if (!preg_match('/[\w-]+/', $name)) {
            throw new \InvalidArgumentException(sprintf('Invalid button name [%s] given', $name));
        }

        return $this;
    }

    protected function processType(&$type): TableButtonBuilderContract
    {
        if (!class_exists($type)) {
            throw new \InvalidArgumentException(sprintf('Invalid button type [%s] given', $type));
        }

        return $this;
    }

    protected function processOptions(Table $table, $name, $type, &$options): TableButtonBuilderContract
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
