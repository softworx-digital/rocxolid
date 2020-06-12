<?php

namespace Softworx\RocXolid\Models\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
// relations
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
// rocXolid controller contracts
use Softworx\RocXolid\Http\Controllers\AbstractCrudController;
// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\FormField;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid model traits
use Softworx\RocXolid\Models\Traits\HasOwner;
use Softworx\RocXolid\Models\Traits\HasAttributes;
use Softworx\RocXolid\Models\Traits\HasRelationships;
use Softworx\RocXolid\Models\Traits\HasTitleColumn;
// rocXolid components
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer;

/**
 * @todo: subject to refactoring
 */
trait Crudable
{
    use HasOwner;
    use HasAttributes;
    use HasRelationships;
    use HasTitleColumn;
    use OnActions\RepositoryActions;

    // @todo: revise
    public static function getAuthorizationParameter(): ?string
    {
        return null;
    }

    public function getModelViewerComponent(?string $view_package = null): CrudModelViewer
    {
        $model_viewer = $this->getCrudController()->getModelViewerComponent($this);

        if (!is_null($view_package)) {
            $model_viewer->setViewPackage($view_package);
        }

        return $model_viewer;
    }

    public function setModelViewerComponentProperties(CrudModelViewer &$model_viewer_component): CrudableModel
    {
        return $this;
    }

    public function provideDomIdParam(): string
    {
        return collect([ $this->getModelName(), $this->getKey() ])->filter()->join(':');
    }

    // @todo: revise
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

    /**
     * Check for model existance (being persisted).
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->exists;
    }

    /**
     * Business rules to prevent model instances to be created.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public function canBeCreated(Request $request): bool
    {
        return true;
    }

    /**
     * Business rules to prevent model instances to be updated.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public function canBeUpdated(Request $request): bool
    {
        return true;
    }

    /**
     * Business rules to prevent model instances to be deleted.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public function canBeDeleted(Request $request): bool
    {
        return true;
    }

    /**
     * Obtain CRUD controller this model is assigned to by default.
     *
     * @return \Softworx\RocXolid\Http\Controllers\AbstractCrudController
     */
    public function getCrudController(): AbstractCrudController
    {
        return app($this->getCrudControllerType());
    }

    /**
     * Obtain CRUD controller type.
     *
     * @return string
     */
    private function getCrudControllerType(): string
    {
        if (property_exists($this, 'controller_type')) {
            return static::$controller_type;
        }

        return $this->guessCrudControllerType();
    }

    /**
     * Naively guess the CRUD controller type based on model namespace.
     *
     * @return string
     */
    private function guessCrudControllerType(): string
    {
        return sprintf('\%s\%s', str_replace('Models', 'Http\Controllers', (new \ReflectionClass($this))->getName()), 'Controller');
    }

    /**
     * Get CRUD controller route.
     *
     * @param string $method
     * @param array $params
     * @return string
     */
    public function getControllerRoute(string $method = 'show', array $params = []): string
    {
        $action = sprintf('%s@%s', $this->getCrudControllerType(), $method);

        return action($action, [ $this ] + $this->getDefaultControllerRouteParams($method) + $params);
    }

    /**
     * Obtain default params for controller route.
     *
     * @param string $method
     * @return array
     */
    protected function getDefaultControllerRouteParams(string $method): array
    {
        return [];
    }

    /**
     * Obtain App (namespace) controller type.
     *
     * @return string
     */
    private function getAppControllerType(): string
    {
        if (property_exists($this, 'app_controller_type')) {
            return static::$app_controller_type;
        }

        return $this->guessAppControllerType();
    }

    /**
     * Naively guess the App (namespace) controller type based on model namespace.
     *
     * @return string
     */
    private function guessAppControllerType(): string
    {
        return sprintf('\App\Http\Controllers\%sController', (new \ReflectionClass($this))->getShortName());
    }

    /**
     * Get App (namespace) controller route.
     *
     * @param string $method
     * @param array $params
     * @return string
     */
    public function getAppControllerRoute(string $method = 'show', array $params = []): string
    {
        $action = sprintf('%s@%s', $this->getAppControllerType(), $method);

        return action($action, [ $this ] + $params);
    }

    /**
     * Retrieve model's route param key name.
     *
     * @return string
     */
    public function getRouteParamKeyName(): string
    {
        return sprintf('%s_%s', Str::snake((new \ReflectionClass($this))->getShortName()), $this->getKeyName());
    }

    /**
     * Create route params for model's relation actions.
     *
     * @param string $attribute Name of the attribute - relation on parent's side.
     * @param string $relation_name Name of the relation on child's side.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model Parent model.
     * @return array
     */
    public function getRouteRelationParam(string $attribute, string $relation_name, ?CrudableModel $model = null): array
    {
        $relation = $this->{$relation_name}();

        if ($relation instanceof MorphTo) {
            return [
                sprintf('%s[model_attribute]', FormField::SINGLE_DATA_PARAM) => $attribute,
                sprintf('%s[relation]', FormField::SINGLE_DATA_PARAM) => $relation_name,
            ] + ($model ? [
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $relation->getMorphType()) => $model->getModelName(),
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $relation->getForeignKeyName()) => $model->getKey()
            ] : [
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $relation->getMorphType()) => $this->$relation_name->getModelName(),
            ]);
        } elseif ($relation instanceof MorphToMany) {
            return [
                sprintf('%s[model_attribute]', FormField::SINGLE_DATA_PARAM) => $attribute,
                sprintf('%s[relation]', FormField::SINGLE_DATA_PARAM) => $relation_name,
            ] + ($model ? [
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $relation->getMorphType()) => $model->getModelName(),
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $relation->getForeignKeyName()) => $model->getKey()
            ] : [
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $relation->getMorphType()) => $this->$relation_name->getModelName(),
            ]);
        } elseif ($relation instanceof BelongsTo) {
            return [
                sprintf('%s[model_attribute]', FormField::SINGLE_DATA_PARAM) => $attribute,
                sprintf('%s[relation]', FormField::SINGLE_DATA_PARAM) => $relation_name,
            ] + ($model ? [
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $relation->getForeignKeyName()) => $model->getKey()
            ] : []);
        }/* elseif ($relation instanceof HasMany) { // @todo: finish support of this relation type
            return [
                sprintf('%s[model_attribute]', FormField::SINGLE_DATA_PARAM) => $attribute,
                sprintf('%s[relation]', FormField::SINGLE_DATA_PARAM) => $relation_name,
            ] + ($model ? [
                sprintf('%s[%s]', FormField::SINGLE_DATA_PARAM, $model->getRouteParamKeyName()) => $model->getKey()
            ] : []);
        }*/ else {
            throw new \RuntimeException(sprintf(
                'Unsupported relation type [%s] for [%s::%s()] in [%s::%s()]',
                get_class($relation),
                get_class($this),
                $relation_name,
                get_class($this),
                'getRouteRelationParam'
            ));
        }
    }

    /**
     * Get attributes for 'show' action.
     *
     * @param array $except
     * @param array $with
     */
    public function getShowAttributes(array $except = [], array $with = []): array
    {
        $attributes = $this->getAttributes();
        $attributes = array_diff_key($attributes, array_flip(array_merge($this->getSystemAttributes(), $this->getHidden())), array_flip($except)) + $with;
        // @todo: you can do better than checking substring
        $attributes = array_filter($attributes, function ($attribute) {
            return (substr($attribute, -3) != '_id');
        }, ARRAY_FILTER_USE_KEY);

        return $attributes;
    }

    /**
     * Check if to treat attribute as boolean value.
     *
     * @param string $attribute
     * @return bool
     */
    public function isBooleanAttribute(string $attribute): bool
    {
        // @todo: you can do (maybe) better than checking substring
        return (substr($attribute, 0, 3) === 'is_');
    }

    /**
     * Check if attribute value is in JSON format.
     *
     * @param string $attribute
     * @return bool
     */
    public function isJsonAttribute(string $attribute): bool
    {
        // @todo: you can do (maybe) better than checking substring
        return (substr($attribute, -5) === '_json');
    }

    /**
     * Check if attribute value represents hex color.
     *
     * @param string $attribute
     * @return bool
     */
    public function isColorAttribute(string $attribute): bool
    {
        // @todo: you can do (maybe) better than checking substring
        return (substr($attribute, -5) === 'color');
    }
}
