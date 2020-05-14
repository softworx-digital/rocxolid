<?php

namespace Softworx\RocXolid\Tables;

// rocXolid contracts
use Softworx\RocXolid\Contracts\Optionable;
use Softworx\RocXolid\Contracts\Translatable;
// rocXolid traits
use Softworx\RocXolid\Traits\Translatable as TranslatableTrait;
use Softworx\RocXolid\Traits\MethodOptionable;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Table;

/**
 * Table elment abstraction.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * */
abstract class AbstractTableElement implements Optionable, Translatable
{
    use TranslatableTrait;
    use MethodOptionable;

    /**
     * Parent table reference.
     *
     * @var \Softworx\RocXolid\Tables\Contracts\Table
     */
    protected $table;

    /**
     * Name of the element.
     *
     * @var string
     */
    protected $name;

    /**
     * Type of the element.
     *
     * @var string
     */
    protected $type;

    /**
     * Default element options.
     * The valid options structure depends on element type.
     *
     * Example:
     *
     * 'template' => 'rocXolid::table.<element>.text',
     * 'type-template' => 'text',
     * 'attributes' => [
     *     'class' => 'table-control'
     * ],
     * 'wrapper' => [
     *     'attributes' => [
     *         'class' => 'table-group'
     *     ]
     * ],
     * 'label' => [
     *     'title' => 'name',
     *     'attributes' => [
     *         'class' => 'control-label col-md-2 col-sm-2 col-xs-12',
     *         'for' => 'name'
     *     ],
     * ],
     * 'validation' => [
     *     'rules' => [
     *         'required',
     *         'max:255',
     *         'min:2',
     *         'active_url',
     *     ],
     *     'error' => [
     *         'attributes' => [
     *             'class' => 'has-error'
     *         ]
     *     ]
     * ],
     */
    protected $default_options = [];

    /**
     * Constructor.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Table $table
     * @param string $name
     * @param string $type
     * @param array $options
     */
    public function __construct(Table $table, string $name, string $type, array $options = [])
    {
        $options = array_replace_recursive($this->default_options, $options);

        $this
            ->setTable($table)
            ->setName($name)
            ->setType($type)
            ->setOptions($options);
    }

    /**
     * {@inheritDoc}
     */
    public function getComponentClass(): string
    {
        return static::$component_class;
    }

    /**
     * Set table element reference to parent table.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Table $table
     * @return \Softworx\RocXolid\Tables\AbstractTableElement
     */
    protected function setTable(Table $table): AbstractTableElement
    {
        $this->table = $table;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTable(): Table
    {
        return $this->table;
    }

    /**
     * Set system name of the element.
     *
     * @param string $name
     * @return \Softworx\RocXolid\Tables\AbstractTableElement
     */
    protected function setName(string $name): AbstractTableElement
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set table element type.
     *
     * @param string $type
     * @return \Softworx\RocXolid\Tables\AbstractTableElement
     */
    protected function setType(string $type): AbstractTableElement
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Check if column is of array type.
     *
     * @return bool
     */
    public function isArray(): bool
    {
        return $this->getOption('component.array', false);
    }
}
