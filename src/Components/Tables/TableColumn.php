<?php

namespace Softworx\RocXolid\Components\Tables;

use Softworx\RocXolid\Contracts\Renderable;
use Softworx\RocXolid\Tables\Columns\Contracts\Column;
use Softworx\RocXolid\Components\AbstractOptionableComponent;
use Softworx\RocXolid\Components\Contracts\TableColumnable as ComponentTableColumnable;

class TableColumn extends AbstractOptionableComponent implements ComponentTableColumnable
{
    const ARRAY_TEMPLATE_NAME = 'array';

    protected $table_column;

    public function setTableColumn(Column $table_column): ComponentTableColumnable
    {
        $this->table_column = $table_column;

        $this->setOptions($this->table_column->getOption('component'));

        // @todo: kinda "hotfixed", you can do better
        if ($view_package = $this->getOption('view-package', false)) {
            $this->setViewPackage($view_package);
        }

        return $this;
    }

    public function getTableColumn(): Column
    {
        if (is_null($this->table_column)) {
            throw new \RuntimeException(sprintf('Table column is not set yet to [%s] component', get_class($this)));
        }

        return $this->table_column;
    }

    public function getDefaultTemplateName(): string
    {
        return $this->getTableColumn()->isArray()
             ? static::ARRAY_TEMPLATE_NAME
             : parent::getDefaultTemplateName();
    }

    public function getModelValue()
    {
        if (is_null($this->getOption('model', null))) {
            throw new \RuntimeException(sprintf('Model is not set yet to [%s] component', get_class($this)));
        }

        $attribute = $this->getTableColumn()->getName();

        return $this->getOption('model')->$attribute;
    }

    public function getOrderRoute()
    {
        $repository = $this->getTableColumn()->getTable();

        if ($repository->isOrderColumn($this->getTableColumn())) {
            $direction = $repository->isOrderDirection('asc') ? 'desc' : 'asc';
        } else {
            $direction = 'asc';
        }

        return $repository->getOrderByRoute($this->getTableColumn()->getName(), $direction);
    }

    public function setPreRenderProperties(...$elements): Renderable
    {
        $table = $elements[0];
        $model = $elements[1];
        $controller = $table->getTable()->getController();

        if ($this->hasOption('action')) {
            if ($this->getOption('ajax', false)) {
                $this->mergeOptions([
                    'attributes' => [
                        'data-ajax-url' => $controller->getRoute($this->getOption('action'), $model, $this->getOption('route-params', []))
                    ]
                ]);
            } else {
                $this->setOption('url', $controller->getRoute($this->getOption('action'), $model, $this->getOption('route-params', [])));
            }
        } elseif ($this->hasOption('tel')) {
            $this->setOption('url', sprintf('tel:%s', $model->{$this->getOption('tel')}));
        } elseif ($this->hasOption('mailto')) {
            $this->setOption('url', sprintf('mailto:%s', $model->{$this->getOption('mailto')}));
        }

        if ($this->hasOption('attributes') && ($title = $this->getOption('attributes.title-key', false))) {
            $this->mergeOptions([
                'attributes' => [
                    'title' => $this->translate($title)
                ]
            ]);
        }

        return $this;
    }

    public function getTranslationKey(string $key): string
    {
        return sprintf('column.%s', $key);
    }
}
