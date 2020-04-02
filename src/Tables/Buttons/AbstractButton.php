<?php

namespace Softworx\RocXolid\Tables\Buttons;

// contracts
use Softworx\RocXolid\Contracts\Optionable;
use Softworx\RocXolid\Contracts\Translatable;
use Softworx\RocXolid\Tables\Contracts\Repository;
use Softworx\RocXolid\Tables\Contracts\Button;
use Softworx\RocXolid\Tables\Contracts\Buttonable;
// traits
use Softworx\RocXolid\Traits\MethodOptionable as MethodOptionableTrait;
use Softworx\RocXolid\Traits\Translatable as TranslatableTrait;
use Softworx\RocXolid\Tables\Buttons\Traits\ComponentOptionsSetter as ComponentOptionsSetterTrait;
// components
use Softworx\RocXolid\Components\Tables\TableButton;

/**
 *
 */
abstract class AbstractButton implements Button, Optionable, Translatable
{
    use MethodOptionableTrait;
    use TranslatableTrait;
    use ComponentOptionsSetterTrait;

    protected static $component_class = TableButton::class;
    /**
     * Name of the column.
     *
     * @var string
     */
    protected $name;
    /**
     * Type of the column.
     *
     * @var string
     */
    protected $type;
    /**
     * @var Repository
     */
    protected $repository;
    /**
     * 'template' => 'rocXolid::repository.column.text',
     * 'type-template' => 'text',
     * 'attributes' => [
     *     'class' => 'repository-control'
     * ],
     * 'wrapper' => [
     *     'attributes' => [
     *         'class' => 'repository-group'
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
     * @param string $name
     * @param string $type
     * @param Repository $repository
     * @param Buttonable $parent
     * @param array $options
     */
    public function __construct($name, $type, Repository $repository, array $options = [])
    {
        $options = array_replace_recursive($this->default_options, $options);

        $this
            ->setName($name)
            ->setType($type)
            ->setRepository($repository)
            ->setOptions($options);
    }

    /**
     * Set system name of the column.
     *
     * @param string $name
     * @return $this
     */
    protected function setName($name): Button
    {
        $this->name = $name;

        return $this;
    }

    protected function setType($type): Button
    {
        $this->type = $type;

        return $this;
    }

    protected function setRepository(Repository $repository): Button
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Get system name of the column.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get system name of the column.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Repository
     */
    public function getRepository(): Repository
    {
        return $this->repository;
    }

    public function isArray()
    {
        return $this->getOption('component.array', false);
    }

    public function getComponentClass()
    {
        return static::$component_class;
    }
}
