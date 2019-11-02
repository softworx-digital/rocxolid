<?php

namespace Softworx\RocXolid\Components\Tables;

use Softworx\RocXolid\Contracts\Translatable;
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Repositories\Contracts\Column;
use Softworx\RocXolid\Components\AbstractOptionableComponent;
use Softworx\RocXolid\Components\Contracts\TableColumnable as ComponentTableColumnable;

class TableColumn extends AbstractOptionableComponent implements ComponentTableColumnable
{
    const ARRAY_TEMPLATE_NAME = 'array';

    protected $table_column;

    public static function buildInTable(Translatable $table, Column $table_column)
    {
        return static::build()
            ->setTranslationPackage($table->getTranslationPackage())
            ->setTranslationParam($table->getTranslationParam())
            ->setTableColumn($table_column);
    }

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

    public function getTranslationKey(string $key): string
    {
        return sprintf('column.%s', $key);
    }
}
