<?php

namespace Softworx\RocXolid\Models\Traits;

use App;
use DB;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
// relations
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
// components
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer;

/**
 * @TODO: refactor?
 */
trait Crudable
{
    public function getModelViewerComponent()
    {
        return App::make($this->getControllerClass())->getModelViewerComponent($this);
    }

    public function getExtraAttributes()
    {
        return $this->extra;
    }

    public function getSystemAttributes()
    {
        return $this->system;
    }

    public function getAllAttributes()
    {
        return array_unique(array_merge($this->getFillable(), $this->getHidden(), $this->getSystemAttributes()));
    }

    public function getRowClass()
    {
        return null;
    }

    public function getModelName($singular = true)
    {
        if (property_exists($this, 'model_name')) {
            $name = static::$model_name;
        } else {
            $name = Str::kebab((new \ReflectionClass($this))->getShortName());
        }

        return $singular ? $name : str_plural($name);
    }

    public function resolvePolymorphism($data, $action = null)
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
                            throw new \InvalidArgumentException(sprintf('Cannot resolve polymorph param [%s] for [%s], provide either [%s] method or configure in [rocXolid.main.polymorphism.%s]', $attribute, static::class, $method, $value));
                        }

                        $this->$attribute = $type;
                        $this->$id_attribute = $data[$id_attribute];
                    }
                }
            }
        }

        return $this;
    }

    public function fillRelationships($data, $action = null)
    {
        foreach ($this->getRelationshipMethods() as $method) {
            if ($this->$method() instanceof BelongsTo) {
                $attribute = sprintf('%s_id', $method);

                if (array_key_exists($attribute, $data) && !empty($data[$attribute])) {
                    $associate = $this->$method()->getRelated()->findOrFail($data[$attribute]);

                    $this->$method()->associate($associate);
                }
            } elseif ($this->$method() instanceof HasMany) {
                $attribute = $method;

                if (array_key_exists($attribute, $data) && !empty($data[$attribute])) {
                    $objects = [];

                    foreach ($data[$attribute] as $id) {
                        $objects[] = $this->$method()->getRelated()->findOrFail($id);
                    }

                    $this->$method()->saveMany($objects);
                }
            } elseif ($this->$method() instanceof BelongsToMany) {
                $attribute = $method;

                if (array_key_exists($attribute, $data)) {
                    $this->$method()->sync($data[$attribute]);
                }
            }
        }

        return $this;
    }

    public function fillCustom($data, $action = null)
    {
        return $this;
    }

    public function beforeSave($data, $action = null)
    {
        return $this;
    }

    public function afterSave($data, $action = null)
    {
        return $this;
    }

    public function beforeDelete()
    {
        return $this;
    }

    public function afterDelete()
    {
        return $this;
    }

    public function canBeDeleted()
    {
        return static::$can_be_deleted;
    }

    public function getCrudController()
    {
        return App::make($this->getControllerClass());
    }

    public function getControllerClass()
    {
        if (property_exists($this, 'controller_class')) {
            return static::$controller_class;
        }

        return $this->guessControllerClass();
    }

    public function guessControllerClass()
    {
        return sprintf('\%s\%s', str_replace('Models', 'Http\Controllers', (new \ReflectionClass($this))->getName()), 'Controller');
    }

    public function getAppControllerClass()
    {
        if (property_exists($this, 'app_controller_class')) {
            return static::$app_controller_class;
        }

        return $this->guessAppControllerClass();
    }

    public function guessAppControllerClass()
    {
        return sprintf('\App\Http\Controllers\%sController', (new \ReflectionClass($this))->getShortName());
    }

    public function getControllerRoute($method = 'show', $params = []): string
    {
        $action = sprintf('%s@%s', $this->getControllerClass(), $method);

        return action($action, [$this] + $params);
    }

    public function getAppControllerRoute($method = 'show', $params = []): string
    {
        $action = sprintf('%s@%s', $this->getAppControllerClass(), $method);

        return action($action, [$this] + $params);
    }

    public function getShowAttributes($except = [], $with = [])
    {
        $attributes = $this->getAttributes();
        $attributes = array_diff_key($attributes, array_flip($this->getSystemAttributes()), array_flip($except)) + $with;
        // @todo: you can do better than checking substring
        $attributes = array_filter($attributes, function ($attribute) {
            return (substr($attribute, -3) != '_id');
        }, ARRAY_FILTER_USE_KEY);

        return $attributes;
    }

    public function isBooleanAttribute($attribute)
    {
        // @todo: you can do (maybe) better than checking substring
        return (substr($attribute, 0, 3) === 'is_');
    }

    public function isJsonAttribute($attribute)
    {
        return false;
    }

    public function isColorAttribute($attribute)
    {
        // @todo: you can do (maybe) better than checking substring
        return (substr($attribute, -5) === 'color');
    }

    public function getRelationshipMethods($except = []): array
    {
        if (!property_exists($this, 'relationships')) {
            return [];
        }

        if (!is_array($except)) {
            $except = [ $except ];
        }

        return array_diff($this->relationships, $except);
    }

    public function getUploadPath()
    {
        return sprintf('%s/%s', strtolower((new \ReflectionClass($this))->getShortName()), $this->id);
    }

    public function userCan($method_group)
    {
        if (!config('rocXolid.admin.auth.check_permissions', false)) {
            return true;
        }

        $controller_class = sprintf('\%s\%s', str_replace('Models', 'Http\Controllers', (new \ReflectionClass($this))->getName()), 'Controller');
        $permission = sprintf('\%s.%s', $controller_class, $method_group);

        if ($user = Auth::guard('rocXolid')->user()) {
            if (!config('rocXolid.admin.auth.check_permissions_root', false) && $user->isRoot()) {
                return true;
            }

            if ($this->allowPermissionException($user, $method_group, $permission)) {
                return true;
            }

            foreach ($user->permissions as $extra_permission) {
                if (($extra_permission->controller_class === $controller_class) && ($extra_permission->controller_method_group === $method_group)) {
                    return true;
                } elseif (($method_group === 'read-only') && (($extra_permission->controller_class === $controller_class) && ($extra_permission->controller_method_group === 'write'))) {
                    return true;
                }
            }

            foreach ($user->roles as $role) {
                foreach ($role->permissions as $permission) {
                    if (($permission->controller_class === $controller_class) && ($permission->controller_method_group === $method_group)) {
                        return true;
                    } elseif (($method_group === 'read-only') && (($permission->controller_class === $controller_class) && ($permission->controller_method_group === 'write'))) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    // @todo: type hints
    protected function allowPermissionException($user, $method_group, $permission)
    {
        return false;
    }

    // @TODO: this doesn't belong here
    public function getImageDimensions($attribute)
    {
        if (property_exists($this, 'image_dimensions')) {
            $image_dimensions = $this->image_dimensions;
        } elseif (property_exists($this, 'default_image_dimensions')) {
            $image_dimensions = $this->default_image_dimensions;
        } else {
            throw new \InvalidArgumentException(sprintf('Model [%s] has no image dimensions definition', (new \ReflectionClass($this))->getName()));
        }

        if (!isset($image_dimensions[$attribute])) {
            throw new \InvalidArgumentException(sprintf('Invalid image attribute [%s] requested, [%s] available', $attribute, implode(', ', array_keys($image_dimensions))));
        }

        return $image_dimensions[$attribute];
    }

    public function getImageDimension($attribute, $dimension)
    {
        $image_dimensions = $this->getImageDimensions($attribute);

        if (!isset($image_dimensions[$dimension])) {
            throw new \InvalidArgumentException(sprintf('Invalid dimension [%s] for attribute [%s] requested, [%s] available', $dimension, $attribute, implode(', ', array_keys($image_dimensions))));
        }

        return (object)$image_dimensions[$dimension];
    }
}
