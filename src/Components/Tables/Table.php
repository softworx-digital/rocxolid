<?php

namespace Softworx\RocXolid\Components\Tables;

use Illuminate\Support\Collection;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Table as TableContract;
// rocXolid component contracts
use Softworx\RocXolid\Components\Contracts\Tableable;
// rocXolid components
use Softworx\RocXolid\Components\AbstractOptionableComponent;
// rocXolid table components
use Softworx\RocXolid\Components\Tables\TableFilter;
use Softworx\RocXolid\Components\Tables\TableColumn;
use Softworx\RocXolid\Components\Tables\TableButton;

/**
 * @todo: refactor
 */
class Table extends AbstractOptionableComponent implements Tableable
{
    protected static $filter_component_class = TableFilter::class;

    // @todo: not used - update other component creation to this
    // protected static $column_component_class = TableColumn::class;

    protected static $button_component_class = TableButton::class;

    protected $table;

    protected $filter_components = null;

    protected $column_components = null;

    protected $button_components = null;

    public function setTable(TableContract $table): Tableable
    {
        $this->table = $table;

        $this->setOptions($this->getTable()->getOption('component'));

        $this
            ->loadTableFiltersComponents()
            ->organizeTableFiltersComponents();
        $this
            ->loadTableColumnsComponents()
            ->organizeTableColumnsComponents();
        $this
            ->loadTableButtonsComponents()
            ->organizeTableButtonsComponents();

        return $this;
    }

    public function getTable(): TableContract
    {
        return $this->table;
    }

    public function getTableFiltersComponents(): Collection
    {
        if (is_null($this->filter_components)) {
            throw new \RuntimeException(sprintf('Table filters components not yet loaded for [%s] component', get_class($this)));
        }

        return $this->filter_components;
    }

    public function getTableColumnsComponents(): Collection
    {
        if (is_null($this->column_components)) {
            throw new \RuntimeException(sprintf('Table columns components not yet loaded for [%s] component', get_class($this)));
        }

        return $this->column_components;
    }

    public function getTableButtonsComponents(): Collection
    {
        if (is_null($this->button_components)) {
            throw new \RuntimeException(sprintf('Table buttons components not yet loaded for [%s] component', get_class($this)));
        }

        return $this->button_components;
    }

    public function getPaginationLinksViewPath()
    {
        return $this->getRenderingService()->getViewPath($this, 'include.pagination-ajax');
    }

    protected function loadTableFiltersComponents(): Tableable
    {
        $this->filter_components = collect();

        foreach ($this->getTable()->getFilters() as $filter) {
            $this->filter_components->put(
                $filter->getName(),
                $this->buildSubComponent(static::$filter_component_class)->setTableFilter($filter)
            );
        }

        return $this;
    }

    protected function organizeTableFiltersComponents(): Tableable
    {
        return $this;
    }

    protected function loadTableColumnsComponents(): Tableable
    {
        $this->column_components = collect();

        foreach ($this->getTable()->getColumns() as $column) {
            $this->column_components->put(
                $column->getName(),
                // $this->buildSubComponent(static::$column_component_class)->setTableColumn($column)
                $this->buildSubComponent($column->getComponentClass())->setTableColumn($column)
            );
        }

        return $this;
    }

    protected function organizeTableColumnsComponents(): Tableable
    {
        return $this;
    }

    protected function loadTableButtonsComponents(): Tableable
    {
        $this->button_components = collect();

        foreach ($this->getTable()->getButtons() as $button) {
            $this->button_components->put(
                $button->getName(),
                $this->buildSubComponent(static::$button_component_class)->setButton($button)
            );
        }

        return $this;
    }

    protected function organizeTableButtonsComponents(): Tableable
    {
        return $this;
    }
}
