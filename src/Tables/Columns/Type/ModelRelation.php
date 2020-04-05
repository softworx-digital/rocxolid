<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Tables\Columns\Contracts\Column;
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
use Softworx\RocXolid\Tables\Columns\AbstractColumn;

class ModelRelation extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'model-relation',
        /*
        // field HTML attributes
        'attributes' => [
            'class' => 'flat'
        ],
        */
    ];

    public function setRelation($relation): Column
    {
        $this->getOptions()->put('relation', $relation);

        return $this;
    }

    public function getRelationModel(CrudableModel $model): CrudableModel
    {
        return $model->{$this->getOption('relation.name')}()->getRelated();
    }

    public function getRelationModelClass(CrudableModel $model): string
    {
        return get_class($model->{$this->getOption('relation.name')}()->getRelated());
    }

    public function getRelationItems(CrudableModel $model): Collection
    {
        return $model->{$this->getOption('relation.name')}()->get();
    }

    protected function setAjax($ajax): Column
    {
        $this->setComponentOptions('ajax', true);

        return $this;
    }
}
