<?php

namespace Softworx\RocXolid\Components\Tables;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Components\AbstractOptionableComponent;
use Softworx\RocXolid\Components\Contracts\Repositoryable as ComponentRepositoryable;
use Softworx\RocXolid\Components\Tables\TableFilter;
use Softworx\RocXolid\Components\Tables\TableColumn;
use Softworx\RocXolid\Components\Tables\TableButton;

class Table extends AbstractOptionableComponent implements ComponentRepositoryable
{
    protected static $filter_component_class = TableFilter::class;

    protected static $column_component_class = TableColumn::class;

    protected static $button_component_class = TableButton::class;

    protected $repository;

    protected $filter_components = null;

    protected $column_components = null;

    protected $button_components = null;

    public function setRepository(Repository $repository): ComponentRepositoryable
    {
        $this->repository = $repository;

        $this->setOptions($this->getRepository()->getOption('component'));

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

    public function getRepository(): Repository
    {
        return $this->repository;
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
        return $this->getViewService()->getViewPath($this, 'include.pagination-ajax');
    }

    protected function loadTableFiltersComponents(): ComponentRepositoryable
    {
        $this->filter_components = new Collection();

        foreach ($this->getRepository()->getFilters() as $filter) {
            $this->filter_components->put(
                $filter->getName(),
                $this->buildSubComponent(static::$filter_component_class)->setTableFilter($filter)
            );
        }

        return $this;
    }

    protected function organizeTableFiltersComponents(): ComponentRepositoryable
    {
        return $this;
    }

    protected function loadTableColumnsComponents(): ComponentRepositoryable
    {
        $this->column_components = new Collection();

        foreach ($this->getRepository()->getColumns() as $column) {
            $this->column_components->put(
                $column->getName(),
                $this->buildSubComponent(static::$column_component_class)->setTableColumn($column)
            );
        }

        return $this;
    }

    protected function organizeTableColumnsComponents(): ComponentRepositoryable
    {
        return $this;
    }

    protected function loadTableButtonsComponents(): ComponentRepositoryable
    {
        $this->button_components = new Collection();

        foreach ($this->getRepository()->getButtons() as $button) {
            $this->button_components->put(
                $button->getName(),
                $this->buildSubComponent(static::$button_component_class)->setButton($button)
            );
        }

        return $this;
    }

    protected function organizeTableButtonsComponents(): ComponentRepositoryable
    {
        return $this;
    }
}
