<?php

namespace Softworx\RocXolid\Tables\Builders;

use Softworx\RocXolid\Tables\Contracts\Table;
use Softworx\RocXolid\Tables\Buttons\Contracts\Button;
use Softworx\RocXolid\Tables\Builders\Contracts\TableButtonBuilder as TableButtonBuilderContract;

/**
 * Builds data table row buttons for with array based definition.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo identify builders' common methods and unify them in abstract class
 */
class TableButtonBuilder implements TableButtonBuilderContract
{
    private static $required_button_settings = [
        'type',
        'options',
    ];

    /**
     * {@inheritDoc}
     */
    public function addDefinitionButtons(Table $table, array $definition): TableButtonBuilderContract
    {
        $buttons = [];

        $this
            ->validateButtonsDefinition($definition)
            ->processButtonsDefinition($table, $definition, $buttons);

        $table->setButtons(collect($buttons));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function makeButton(Table $table, string $type, string $name, array $options = []): Button
    {
        $field = new $type($table, $name, $type, $options);

        return $field;
    }

    protected function processDefinition(Table $table, array $definition, array &$items, ?string $name_prefix = null): TableButtonBuilderContract
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

    protected function validateButtonsDefinition(array $definition): TableButtonBuilderContract
    {
        if (!isset($definition['buttons'])) {
            throw new \InvalidArgumentException(sprintf('Buttons not defined in definition [%s]', print_r($definition, true)));
        } elseif (!is_bool($definition['buttons']) && !is_array($definition['buttons'])) {
            throw new \InvalidArgumentException(sprintf('Invalid buttons definition [%s], boolean or array expected', gettype($definition['buttons'])));
        }

        return $this;
    }

    protected function processButtonsDefinition(Table $table, array $definition, array &$buttons, ?string $name_prefix = null): TableButtonBuilderContract
    {
        $this->processDefinition($table, $definition['buttons'], $buttons, $name_prefix);

        return $this;
    }

    protected function processSettings(array &$settings, ?string &$type, ?array &$options): TableButtonBuilderContract
    {
        foreach (self::$required_button_settings as $required) {
            if (!isset($settings[$required])) {
                throw new \InvalidArgumentException(sprintf('Required button setting [%s] not found in settings: %s', $required, print_r($settings)));
            }
        }

        extract($settings); // $type, $options

        return $this;
    }

    protected function processName(string &$name, ?string $name_prefix): TableButtonBuilderContract
    {
        $name = is_null($name_prefix) ? $name : sprintf('%s-%s', $name_prefix, $name);

        if (!preg_match('/[\w-]+/', $name)) {
            throw new \InvalidArgumentException(sprintf('Invalid button name [%s] given', $name));
        }

        return $this;
    }

    protected function processType(string &$type): TableButtonBuilderContract
    {
        if (!class_exists($type)) {
            throw new \InvalidArgumentException(sprintf('Invalid button type [%s] given', $type));
        }

        return $this;
    }

    protected function processOptions(Table $table, string $name, string $type, array &$options): TableButtonBuilderContract
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
