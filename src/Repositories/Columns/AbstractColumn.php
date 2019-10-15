<?php

namespace Softworx\RocXolid\Repositories\Columns;

// contracts
use Softworx\RocXolid\Contracts\Optionable;
use Softworx\RocXolid\Contracts\Translatable;
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Repositories\Contracts\Column;
use Softworx\RocXolid\Repositories\Contracts\Columnable;
// traits
use Softworx\RocXolid\Traits\MethodOptionable as MethodOptionableTrait;
use Softworx\RocXolid\Traits\Translatable as TranslatableTrait;
use Softworx\RocXolid\Repositories\Columns\Traits\ComponentOptionsSetter as ComponentOptionsSetterTrait;
// components
use Softworx\RocXolid\Components\Tables\TableColumn;

/**
 *
 */
abstract class AbstractColumn implements Column, Optionable, Translatable
{
    use MethodOptionableTrait;
    use TranslatableTrait;
    use ComponentOptionsSetterTrait;

    protected static $component_class = TableColumn::class;
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
     * @param Columnable $parent
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
    protected function setName($name): Column
    {
        $this->name = $name;

        return $this;
    }

    protected function setType($type): Column
    {
        $this->type = $type;

        return $this;
    }

    protected function setRepository(Repository $repository): Column
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
}
