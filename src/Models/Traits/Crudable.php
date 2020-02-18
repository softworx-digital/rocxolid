<?php

namespace Softworx\RocXolid\Models\Traits;

use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
// relations
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\FormField;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid model traits
use Softworx\RocXolid\Models\Traits\HasOwner;
use Softworx\RocXolid\Models\Traits\HasTitleColumn;
use Softworx\RocXolid\Models\Traits\HasRelationships;
// rocXolid components
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer;

/**
 * @todo: subject to refactoring
 */
trait Crudable
{
    use HasOwner;
    use HasTitleColumn;
    use HasRelationships;

    public static function getAuthorizationParameter(): ?string
    {
        return null;
    }

    public function getModelViewerComponent(string $view_package = null)
    {
        $model_viewer = app($this->getControllerClass())->getModelViewerComponent($this);

        if (!is_null($view_package)) {
            $model_viewer->setViewPackage($view_package);
        }

        return $model_viewer;
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

        return $singular ? $name : Str::plural($name);
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
        return app($this->getControllerClass());
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

        return action($action, [ $this ] + $params);
    }

    public function getAppControllerRoute($method = 'show', $params = []): string
    {
        $action = sprintf('%s@%s', $this->getAppControllerClass(), $method);

        return action($action, [ $this ] + $params);
    }

    /**
     * Create route params for model's relation actions.
     *
     * @param string $attribute Name of the attribute - relation on parent's side.
     * @param string $relation Name of the relation on child's side.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model Parent model.
     * @return array
     */
    public function getRouteRelationParam(string $attribute, string $relation, ?CrudableModel $model = null): array
    {
        $relation_name = $relation;
        $relation = $this->$relation();

        if ($relation instanceof MorphTo) {
            return [
                sprintf('%s[model_attribute]', FormField::SINGLE_DATA_PARAM) => $attribute,
                sprintf('%s[relation]', FormField::SINGLE_DATA_PARAM) => $relation_name,
            ] + ($model ? [
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $relation->getMorphType()) => Str::kebab((new \ReflectionClass($model))->getShortName()),
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $relation->getForeignKeyName()) => $model->getKey()
            ] : [
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $relation->getMorphType()) => Str::kebab((new \ReflectionClass($this->$relation_name))->getShortName()),
            ]);
        } elseif ($relation instanceof MorphToMany) {
            return [
                sprintf('%s[model_attribute]', FormField::SINGLE_DATA_PARAM) => $attribute,
                sprintf('%s[relation]', FormField::SINGLE_DATA_PARAM) => $relation_name,
            ] + ($model ? [
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $relation->getMorphType()) => Str::kebab((new \ReflectionClass($model))->getShortName()),
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $relation->getForeignKeyName()) => $model->getKey()
            ] : [
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $relation->getMorphType()) => Str::kebab((new \ReflectionClass($this->$relation_name))->getShortName()),
            ]);
        } elseif ($relation instanceof BelongsTo) {
            return [
                sprintf('%s[model_attribute]', FormField::SINGLE_DATA_PARAM) => $attribute,
                sprintf('%s[relation]', FormField::SINGLE_DATA_PARAM) => $relation_name,
            ] + ($model ? [
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $relation->getForeignKeyName()) => $model->getKey()
            ] : []);
        } else {
            throw new \RuntimeException(sprintf(
                'Unsupported relation type [%s] for [%s->%s()]',
                get_class($relation),
                get_class($this),
                $relation->getRelationName(),
            ));
        }
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
        // @todo: you can do (maybe) better than checking substring
        return (substr($attribute, -5) === '_json');
    }

    public function isColorAttribute($attribute)
    {
        // @todo: you can do (maybe) better than checking substring
        return (substr($attribute, -5) === 'color');
    }

    // @TODO: this doesn't belong here
    public function getUploadPath()
    {
        return sprintf('%s/%s', strtolower((new \ReflectionClass($this))->getShortName()), $this->getKey());
    }

    // @TODO: this doesn't belong here
    public function getImageSizes($attribute)
    {
        if (property_exists($this, 'image_sizes')) {
            $image_sizes = $this->image_sizes;
        } elseif (property_exists($this, 'default_image_sizes')) {
            $image_sizes = $this->default_image_sizes;
        } else {
            throw new \InvalidArgumentException(sprintf('Model [%s] has no image sizes definition', (new \ReflectionClass($this))->getName()));
        }

        if (!isset($image_sizes[$attribute])) {
            throw new \InvalidArgumentException(sprintf('Invalid image attribute [%s] requested, [%s] available', $attribute, implode(', ', array_keys($image_sizes))));
        }

        return $image_sizes[$attribute];
    }

    public function getImageSize($attribute, $size)
    {
        $image_sizes = $this->getImageSizes($attribute);

        if (!isset($image_sizes[$size])) {
            throw new \InvalidArgumentException(sprintf('Invalid size [%s] for attribute [%s] requested, [%s] available', $size, $attribute, implode(', ', array_keys($image_sizes))));
        }

        return (object)$image_sizes[$size];
    }
}
