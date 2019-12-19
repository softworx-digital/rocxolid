<?php

namespace Softworx\RocXolid\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;
// contracts
use Softworx\RocXolid\Contracts\Optionable;
use Softworx\RocXolid\Contracts\Translatable;
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Repositories\Contracts\Filter;
use Softworx\RocXolid\Repositories\Contracts\Filterable;
// traits
use Softworx\RocXolid\Traits\MethodOptionable as MethodOptionableTrait;
use Softworx\RocXolid\Traits\Translatable as TranslatableTrait;
use Softworx\RocXolid\Repositories\Filters\Traits\ComponentOptionsSetter as ComponentOptionsSetterTrait;
// components
use Softworx\RocXolid\Components\Tables\TableFilter;

/**
 *
 */
abstract class AbstractFilter implements Filter, Optionable, Translatable
{
    use TranslatableTrait;
    use MethodOptionableTrait;
    use ComponentOptionsSetterTrait;

    protected static $component_class = TableFilter::class;
    /**
     * Name of the filter.
     *
     * @var string
     */
    protected $name;
    /**
     * Type of the filter.
     *
     * @var string
     */
    protected $type;
    /**
     * Value of the filter.
     *
     * @var mixed
     */
    protected $value;
    /**
     * @var Repository
     */
    protected $repository;
    /**
     * 'template' => 'rocXolid::repository.filter.text',
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
     * @param Filterable $parent
     * @param array $options
     */
    public function __construct($name, $type, Repository $repository, array $options = [])
    {
        $options = array_replace_recursive($this->default_options, $options);

        $this
            ->setRepository($repository)
            ->setName($name)
            ->setType($type)
            ->setOptions($options);
    }

    protected function setRepository(Repository $repository): Filter
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Set system name of the filter.
     *
     * @param string $name
     * @return $this
     */
    protected function setName($name): Filter
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFieldName(): string
    {
        return sprintf('%s[%s]', self::DATA_PARAM, $this->name);
    }

    protected function setType($type): Filter
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    //public function getColumnName(Builder $query): string
    public function getColumnName($query): string
    {
        return sprintf('%s.%s', $query->getModel()->getTable(), $this->getName());
    }

    public function setValue($value): Filter
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return Repository
     */
    public function getRepository(): Repository
    {
        return $this->repository;
    }

    public function translate(string $key, array $params = [], bool $use_raw_key = false): string
    {
        /*
        $component_class = static::$component_class;

        return (new $component_class())->setTableFilter($this)->translate($key);
        */
        return __METHOD__ . '@todo';
    }
}
