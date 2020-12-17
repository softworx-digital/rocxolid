<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\MorphOne;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Columns\Contracts\Column;
// rocXolid table columns
use Softworx\RocXolid\Tables\Columns\AbstractColumn;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

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

    public function getRelationItems(Crudable $model): Collection
    {
        // @todo: quick'n'dirty
        if ($this->getOption('relation.crossed', false)) {
            if ($model->{$this->getOption('relation.crossed.name')}()->exists()) {
                return $model
                    ->{$this->getOption('relation.crossed.name')}
                    ->{$this->getOption('relation.name')}()
                    ->where('is_model_primary', 1)->get();
            } else {
                return collect();
            }
        }

        if ($model->{$this->getOption('relation.name')}() instanceof MorphOne) {
            return $model->{$this->getOption('relation.name')}()->get();
        }

        return $model->{$this->getOption('relation.name')}()->where('is_model_primary', 1)->get();
    }

    public function setSize($size): Column
    {
        $this->setComponentOptions('size', $size);

        return $this;
    }
}
