<?php

namespace Softworx\RocXolid\Models\Traits;

use Str;
use Log;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

trait HasRelationships
{
    public function getReflectedRelationshipMethods()
    {
        $reflect = new \ReflectionClass($this);
        $methods = collect($reflect->getMethods())->filter(function($method) {
            return is_subclass_of((string)$method->getReturnType(), \Illuminate\Database\Eloquent\Relations\Relation::class);
        });

        return $methods;
    }

    public function hasRelationshipMethods(): bool
    {
        return !$this->getRelationshipMethods()->isEmpty();
    }

    /**
     * Get all relationship methods to show in model detail screen.
     *
     */
    public function getRelationshipMethods(...$except): Collection
    {
        if (!property_exists($this, 'relationships')) {
            return collect();
        }

        return collect($this->relationships)->except($except ?? []);
    }

    /**
     * Get all model's relationships.
     */
    public function getAllRelationships(): Collection
    {
        $model = new static;

        $relationships = collect();

        foreach ((new \ReflectionClass($model))->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if (($method->class !== get_class($model)) || !empty($method->getParameters()) || ($method->getName() === __FUNCTION__)) {
                continue;
            }

            try {
                $return = $method->invoke($model);

                if ($return instanceof Relation) {
                    $relationships->put($method->getName(), [
                        'type' => (new \ReflectionClass($return))->getShortName(),
                        'model' => (new \ReflectionClass($return->getRelated()))->getName()
                    ]);
                }
            } catch (\ErrorException $e) {
            }
        }

        return $relationships;
    }

    public function resolvePolymorphism(array $data, string $action = null): Crudable
    {
        foreach ($data as $attribute => $value) {
            if (substr($attribute, -5) === '_type') {
                $id_attribute = sprintf('%s_id', substr($attribute, 0, -5));

                if (array_key_exists($id_attribute, $data)) {
                    $method = sprintf('resolvePolymorph%sModel', Str::studly($value));

                    if (method_exists($this, $method)) {
                        $this->$attribute = $this->$method();
                        $this->$id_attribute = $data[$id_attribute];
                    } else {
                        $type = config(sprintf('rocXolid.main.polymorphism.%s', $value));

                        if (!$type) {
                            throw new \InvalidArgumentException(sprintf(
                                'Cannot resolve polymorph param [%s] for [%s], provide either [%s] method or configure in [rocXolid.main.polymorphism.%s]',
                                $attribute,
                                static::class,
                                $method,
                                $value
                            ));
                        }

                        $this->$attribute = $type;
                        $this->$id_attribute = $data[$id_attribute];
                    }
                }
            }
        }

        return $this;
    }

    // @todo: subject to refactoring, don't like the current approach
    public function fillRelationships(array $data, string $action = null): Crudable
    {
        $this->getRelationshipMethods()->each(function($relation) use ($data, $action) {
            // possibility to adjust the data and its structure before assignment
            $adjust_method = sprintf('adjust%sFillData', Str::studly($relation));

            $data = method_exists($this, $adjust_method) ? $this->$adjust_method($data) : $data;

            if ($this->$relation() instanceof BelongsTo) {
                $this->fillBelongsTo($relation, $data);
            } elseif ($this->$relation() instanceof HasMany) {
                $this->fillHasMany($relation, $data);
            } elseif ($this->$relation() instanceof BelongsToMany) {
                $this->fillBelongsToMany($relation, $data);
            }
        });

        return $this;
    }

    protected function fillBelongsTo(string $relation, array $data): Crudable
    {
        $attribute = sprintf('%s_id', $relation);

        if (array_key_exists($attribute, $data) && !empty($data[$attribute])) {
            $associate = $this->$relation()->getRelated()->findOrFail($data[$attribute]);

            $this->$relation()->associate($associate);
        }

        return $this;
    }

    protected function fillHasMany(string $relation, array $data): Crudable
    {
        $attribute = $relation;

        if (array_key_exists($attribute, $data) && !empty($data[$attribute])) {
            $objects = [];

            foreach ($data[$attribute] as $id) {
                $objects[] = $this->$relation()->getRelated()->findOrFail($id);
            }

            $this->$relation()->saveMany($objects);
        }

        return $this;
    }

    protected function fillBelongsToMany(string $relation, array $data): Crudable
    {
        $attribute = $relation;

        if (array_key_exists($attribute, $data)) {
            $this->$relation()->sync($data[$attribute]);
        }

        return $this;
    }
}
