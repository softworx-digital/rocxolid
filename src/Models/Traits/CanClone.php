<?php

namespace Softworx\RocXolid\Models\Traits;

use Illuminate\Support\Collection;
// relations
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
// rocXolid contracts
use Softworx\RocXolid\Models\Contracts\Cloneable;
use Softworx\RocXolid\Models\Contracts\Container;
use Softworx\RocXolid\Models\Contracts\Containee;
// quick n dirty
use Softworx\RocXolid\CMS\Models\HtmlWrapper;

/**
 *
 */
trait CanClone
{
    /**
     * Model relationship methods that can be possibly cloned.
     *
     * @var array
     */
    //protected $clone_relationships = [];

    public function clone(Collection &$clone_log, array $fill = [], array $with_relations = []): Cloneable
    {
        $class = (new \ReflectionClass($this))->getName();

        if ($clone_log->has($class) && array_key_exists($this->id, $clone_log->get($class))) { // prevent once cloned
            return $class::find($clone_log->get($class)[$this->id]);
        }

        $clone = $this->replicate();
        $clone->fill($fill);
        $clone->save();
        $clone->fillClonedAfter($clone_log, $fill, $with_relations);
        //$clone->push(); // handled further

        if (!$clone_log->has($class)) {
            $clone_log->put($class, []);
        }

        $clone_log->put($class, $clone_log->get($class) + [
            $this->id => $clone->id
        ]);

        if ($with_relations) {
            $this->relations = [];
            $this->load($with_relations);

            foreach ($this->getRelations() as $relation => $values) {
                if ($this->$relation() instanceof MorphToMany) {
                    //$clone->$relation()->sync($values);

                    foreach ($values as $value) {
                        $extra_attributes = array_except($value->pivot->getAttributes(), $value->pivot->getForeignKey());
                        $clone->$relation()->attach($value, $extra_attributes);
                    }
                } elseif ($this->$relation() instanceof BelongsTo) {
                    $clone->$relation()->associate($values);
                } elseif ($this->$relation() instanceof HasMany) {
                    $clone->$relation()->saveMany($values);
                } elseif ($this->$relation() instanceof BelongsToMany) {
                    $clone->$relation()->sync($values);
                }
            }
        }

        if ($this instanceof Container) {
            $this->cloneContainees($clone, $clone_log, $fill, $with_relations);
        }

        return $clone;
    }

    protected function cloneContainees($clone, $clone_log, $fill, $with_relations)
    {
        $this->getAllContainees()->each(function ($containee, $index) use ($clone, $clone_log, $fill, $with_relations) {
            if (($containee instanceof Cloneable) && $containee->getContaineePivotData()->is_owned) {
                $containee_clone = $containee->clone($clone_log, $fill, $with_relations);

                $clone->attachContainee($containee->getContaineePivotData()->container_relation, $containee_clone);
            }
        });

        return $this;
    }

    public function buildRelations($clone_log)
    {
        if ($this instanceof Container) {
            $this->getAllContainees()->each(function ($containee, $index) use ($clone_log) {
                $containee->buildRelations($clone_log);
            });
        }

        $class = (new \ReflectionClass($this))->getName();

        foreach ($this->getAttributes() as $attribute => $value) {
            if (substr($attribute, -3) == '_id') {
                $method = camel_case(substr($attribute, 0, -3));

                if (method_exists($this, $method) && ($this->$method() instanceof Relation)) {
                    $related = $this->$method()->getRelated();
                    $class = (new \ReflectionClass($related))->getName();

                    if ($clone_log->has($class) && array_key_exists($value, $clone_log->get($class))) {
                        $this->$attribute = $clone_log->get($class)[$value];
                    }
                }
            }
        }

        $this->buildRelationsAfter($clone_log);

        $this->save();

        return $this;
    }

    protected function fillClonedAfter(Collection &$clone_log, array $fill = [], array $with_relations = []): Cloneable
    {
        return $this;
    }

    protected function buildRelationsAfter(Collection $clone_log): Cloneable
    {
        return $this;
    }

    public function getCloneRelationshipMethods($except = []): array
    {
        if (!property_exists($this, 'clone_relationships')) {
            return [];
        }

        if (!is_array($except)) {
            $except = [ $except ];
        }

        return array_diff($this->clone_relationships, $except);
    }

    public function getCloneableCloned($clone_log)
    {
        $class = (new \ReflectionClass($this))->getName();

        if ($clone_log->has($class) && array_key_exists($this->id, $clone_log->get($class))) {
            return $class::find($clone_log->get($class)[$this->id]);
        }

        return null;
    }

    public function getCloneableOriginal($clone_log)
    {
        $class = (new \ReflectionClass($this))->getName();

        if ($clone_log->has($class) && ($original_id = array_search($this->id, $clone_log->get($class)))) {
            return $class::find($original_id);
        }

        return null;
    }
}
