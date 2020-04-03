<?php

namespace Softworx\RocXolid\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Filter;
// rocXolid table elements
use Softworx\RocXolid\Tables\AbstractTableElement;
// rocXolid table filter traits
use Softworx\RocXolid\Tables\Filters\Traits\ComponentOptionsSetter;
// rocXolid table components
use Softworx\RocXolid\Components\Tables\TableFilter;

/**
 * Table filter field abstraction.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractFilter extends AbstractTableElement implements Filter
{
    use ComponentOptionsSetter;

    /**
     * Component class definition.
     *
     * @var string
     */
    protected static $component_class = TableFilter::class;

    /**
     * Filter field value.
     *
     * @var mixed
     */
    protected $value;

    /**
     * 'template' => 'rocXolid::table.filter.text',
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
     * Get field name used in the filter form.
     *
     * @return string
     */
    public function getFieldName(): string
    {
        return sprintf('%s[%s]', self::DATA_PARAM, $this->getName());
    }

    /**
     * Get fully qualified column name the filter binds to.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return string
     */
    public function getColumnName(Builder $query): string
    {
        return sprintf('%s.%s', $query->getModel()->getTable(), $this->getName());
    }

    /**
     * Set filter value.
     *
     * @param mixed $value
     * @return \Softworx\RocXolid\Tables\Contracts\Filter
     */
    public function setValue($value): Filter
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Retrieve filter value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
