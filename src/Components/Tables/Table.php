<?php

namespace Softworx\RocXolid\Components\Tables;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Components\AbstractOptionableComponent;
use Softworx\RocXolid\Components\Contracts\Repositoryable as ComponentRepositoryable;

class Table extends AbstractOptionableComponent implements ComponentRepositoryable
{
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
            $this->filter_components[$filter->getName()] = (new TableFilter())->setTableFilter($filter);
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
            $this->column_components[$column->getName()] = (new TableColumn())->setTableColumn($column);
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
            $this->button_components[$button->getName()] = (new TableButton())->setButton($button);
        }

        return $this;
    }

    protected function organizeTableButtonsComponents(): ComponentRepositoryable
    {
        return $this;
    }
}
