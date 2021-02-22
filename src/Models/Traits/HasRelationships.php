<?php

namespace Softworx\RocXolid\Models\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations;
// rocXolid model scopes
use Softworx\RocXolid\Models\Scopes\Owned as OwnedScope;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * @todo revise
 */
trait HasRelationships
{
    // @todo kinda hardcoded
    public function isParentSingle()
    {
        return ($this->parent->{$this->model_attribute}() instanceof Relations\MorphOne);
    }

    // @todo kinda hardcoded
    public function isParentPrimary()
    {
        return $this->is_model_primary;
    }

    public function getReflectedRelationshipMethods()
    {
        $reflect = new \ReflectionClass($this);
        $methods = collect($reflect->getMethods())->filter(function (\ReflectionMethod $method) {
            return is_subclass_of($method->getReturnType()->getName(), Relations\Relation::class);
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

        return collect($this->relationships)->diff($except ?? []);
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

                if ($return instanceof Relations\Relation) {
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

    // @todo ugly ! and combine with HasRelationships::resolvePolymorphType()
    // @todo maybe use resource registrar
    public function resolvePolymorphism(Collection $data, string $action = null): Crudable
    {
        $data->each(function ($value, $attribute) use ($data) {
            // eg. model_type
            if (substr($attribute, -5) === '_type') {
                // eg. model_id
                $id_attribute = sprintf('%s_id', substr($attribute, 0, -5));

                if ($data->has($id_attribute)) {
                    $method = sprintf('resolvePolymorph%sModel', Str::studly($value));

                    if (method_exists($this, $method)) {
                        $this->$attribute = $this->$method();
                        $this->$id_attribute = $data->get($id_attribute);
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
                        $this->$id_attribute = $data->get($id_attribute);
                    }
                }
            }
        });

        return $this;
    }

    // @todo ugly
    public function resolvePolymorphType(Collection $data): string
    {
        foreach ($data as $attribute => $value) {
            if (substr($attribute, -5) === '_type') {
                $method = sprintf('resolvePolymorph%sModel', Str::studly($value));

                if (method_exists($this, $method)) {
                    return $this->$method();
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

                    return $type;
                }
            }
        }

        return false;
    }

    // @todo subject to refactoring, don't like the current approach
    public function fillRelationships(Collection $data): Crudable
    {
        $data = $data->toArray(); // @todo use collections

        $this->getRelationshipMethods()->each(function ($relation) use ($data) {
            // possibility to adjust the data and its structure before assignment
            $adjust_method = sprintf('adjust%sFillData', Str::studly($relation));

            $data = method_exists($this, $adjust_method) ? $this->$adjust_method($data) : $data;

            // possibility to do custom relationship handling
            $fill_method = sprintf('fill%s', Str::studly($relation));

            if (method_exists($this, $fill_method)) {
                $this->$fill_method($data);
            } else {
                if ($this->$relation() instanceof Relations\BelongsTo) {
                    $this->fillBelongsTo($relation, $data);
                } elseif ($this->$relation() instanceof Relations\HasMany) {
                    $this->fillHasMany($relation, $data);
                } elseif ($this->$relation() instanceof Relations\BelongsToMany) {
                    $this->fillBelongsToMany($relation, $data);
                }
            }
        });

        return $this;
    }

    protected function fillBelongsTo(string $relation, array $data): Crudable
    {
        $attribute = sprintf('%s_id', $relation);

        if (array_key_exists($attribute, $data) && !empty($data[$attribute])) {
            // @todo kinda "hotfixed"
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

    // @todo switch to collections (arguments)
    protected function fillBelongsToMany(string $relation, array $data, bool $detach = true): Crudable
    {
        $attribute = $relation;
        $data = collect($data);

        if ($data->has($attribute)) {
            $attribute_data = collect($data->get($attribute));

            if (filled($this->$relation()->getPivotColumns())) {
                $i = 0;
                $attribute_data->transform(function (array $pivot, int $id) use (&$i) {
                    $pivot['position'] = $pivot['position'] ?? $i++;
                    return $pivot;
                });
            }

            $this->$relation()->sync($attribute_data, $detach);
        }

        return $this;
    }
}
