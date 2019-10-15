<?php

namespace Softworx\RocXolid\Components\Tables;

use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Components\AbstractOptionableComponent;
use Softworx\RocXolid\Components\Contracts\TableColumnable as ComponentTableColumnable;
use Softworx\RocXolid\Repositories\Contracts\Column;

class TableColumn extends AbstractOptionableComponent implements ComponentTableColumnable
{
    const ARRAY_TEMPLATE_NAME = 'array';

    protected $table_column;

    public function setTableColumn(Column $table_column): ComponentTableColumnable
    {
        $this->table_column = $table_column;

        $this->setOptions($this->table_column->getOption('component'));

        return $this;
    }

    public function getTableColumn(): Column
    {
        if (is_null($this->table_column)) {
            throw new \RuntimeException(sprintf('Table column is not set yet to [%s] component', get_class($this)));
        }

        return $this->table_column;
    }

    public function getDefaultTemplateName()
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
        $repository = $this->getTableColumn()->getRepository();

        if ($repository->isOrderColumn($this->getTableColumn())) {
            $direction = $repository->isOrderDirection('asc') ? 'desc' : 'asc';
        } else {
            $direction = 'asc';
        }

        return $repository->getRoute('order', null, [
            'order_by' => [
                'column' => $this->getTableColumn()->getName(),
                'direction' => $direction,
            ]
        ]);
    }

    protected function getTranslationKey($key, $use_repository_param)
    {
        if (!$use_repository_param) {
            return sprintf('column.%s', $key);
        } elseif ($this->getTableColumn() && $this->getTableColumn()->getRepository()) {
            return sprintf('%s.column.%s', $this->getTableColumn()->getRepository()->getTranslationParam(), $key);
        } else {//if ($this->getController() && $this->getController()->getRepository())
            return '---field--- (' . __METHOD__ . ')';
        }

        return $key;
    }
}
