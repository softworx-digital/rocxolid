<?php

namespace Softworx\RocXolid\Repositories\Columns\Type;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Repositories\Contracts\Column;
use Softworx\RocXolid\Repositories\Columns\AbstractColumn;
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;

class ImageRelation extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'image-relation',
        /*
        // field wrapper
        'wrapper' => false,
        // column HTML attributes
        'attributes' => [
            'class' => 'form-control'
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
        return $model->{$this->getOption('relation.name')}()->where('is_model_primary', 1)->get();
    }

    public function setDimension($dimension): Column
    {
        $this->setComponentOptions('dimension', $dimension);

        return $this;
    }
}
