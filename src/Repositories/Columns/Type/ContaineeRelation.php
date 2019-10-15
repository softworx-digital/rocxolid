<?php

namespace Softworx\RocXolid\Repositories\Columns\Type;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Repositories\Contracts\Column;
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
use Softworx\RocXolid\Repositories\Columns\AbstractColumn;

class ContaineeRelation extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'containee-relation',
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

    public function getRelationItems(CrudableModel $model): Collection
    {
        return $model->getContainees($this->getOption('relation.name'));
    }

    protected function setAjax($ajax): Column
    {
        $this->setComponentOptions('ajax', true);

        return $this;
    }
}
