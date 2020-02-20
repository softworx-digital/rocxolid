<?php

namespace Softworx\RocXolid\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Doctrine\Common\Annotations\AnnotationReader;
// rocXolid services
use Softworx\RocXolid\Services\PackageService;
// rocXolid annotations
use Softworx\RocXolid\Annotations\AuthorizedAction;
use Softworx\RocXolid\Annotations\AuthorizedRelation;
// rocXolid controller contracts
use Softworx\RocXolid\Http\Controllers\Contracts\Crudable as CrudableController;
// rocXolid models contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Service to retrieve rocXolid and App permissions.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class PermissionScannerService
{
    /**
     * Application package service reference.
     *
     * @var \Softworx\RocXolid\Services\PackageService
     */
    protected $package_service;

    /**
     * Annotation reader.
     *
     * @var \Doctrine\Common\Annotations\AnnotationReader
     */
    protected $annotation_reader;

    /**
     * Constructor.
     *
     * @param \Softworx\RocXolid\Services\PackageService $package_service Package service to access .
     */
    public function __construct(PackageService $package_service, AnnotationReader $annotation_reader)
    {
        $this->package_service = $package_service;
        $this->annotation_reader = $annotation_reader;
    }

    public function sourceCodePermissions(): Collection
    {
        return $this->controllersPermissions()->merge($this->modelsPermissions());
    }

    public function persistentPermissions(Model $permission): Collection
    {
        return collect($permission::all()->toArray())->map(function ($item) use ($permission) {
            return collect($item)->only($permission->getFillable())->except('is_enabled')->toArray();
            // return collect($item)->toArray(); // this will get visible, not using right now
        });
    }

    public function isSynchronized(Collection $code_permissions, Collection $saved_permissions): bool
    {
        return $code_permissions->diffRecords($saved_permissions)->isEmpty()
            && $saved_permissions->diffRecords($code_permissions)->isEmpty();
    }

    private function controllersPermissions(): Collection
    {
        $permissions = collect();

        $controllers = collect()
            ->merge($this->getPermissionableControllers('App\\'))
            ->merge($this->getPermissionableControllers('Softworx\\RocXolid\\'));

        $controllers->each(function($controller) use ($permissions) {
            $reflection = new \ReflectionClass($controller);

            // $permissions->put($controller, collect());

            if (Str::startsWith($reflection->getNamespaceName(), 'App\\')) {
                $package = 'app';
            }

            if (Str::startsWith($reflection->getNamespaceName(), 'Softworx\\RocXolid\\')) {
                $namespace_fragment = implode('\\', array_slice(explode('\\', $reflection->getNamespaceName(), 4), 0, 3));
                $service_provider = sprintf('%s\\ServiceProvider', $namespace_fragment);

                $package = $service_provider::getPackageKey();
            }

            $methods = collect($reflection->getMethods(\ReflectionMethod::IS_PUBLIC))->filter(function (&$method) {
                try {
                    $method->annotation = $this->annotation_reader->getMethodAnnotation($method, AuthorizedAction::class);
                } catch (\Throwable $e) {
                    // @todo: nicer handling
                    dd(__METHOD__, $method, $e->getMessage());
                }
                return !is_null($method->annotation);
            })->each(function($method) use ($permissions, $controller, $package) {
                $permissions->push([
                    'name' => $this->getPermissionName($controller::getModelClass(), $method->annotation->getPolicyAbility()),
                    'guard' => 'rocXolid',
                    'package' => $package,
                    'controller_class' => $controller,
                    'model_class' => $controller::getModelClass(),
                    'attribute' => null,
                    'policy_ability_group' => $method->annotation->getPolicyAbilityGroup(),
                    'policy_ability' => $method->annotation->getPolicyAbility(),
                    'scopes' => $method->annotation->getScopes(),
                ]);
            });
        });

        // making it unique because there can be more methods annotated (eg. create & store) + inheritance...
        return $permissions->unique(function ($item) {
            return $item['package'].$item['name'];
        });
    }

    private function modelsPermissions(): Collection
    {
        $permissions = collect();

        $models = collect()
            ->merge($this->getPermissionableModels('App\\'))
            ->merge($this->getPermissionableModels('Softworx\\RocXolid\\'));

        $models->each(function($model) use ($permissions) {
            $reflection = new \ReflectionClass($model);

            if (Str::startsWith($reflection->getNamespaceName(), 'App\\')) {
                $package = 'app';
            }

            if (Str::startsWith($reflection->getNamespaceName(), 'Softworx\\RocXolid\\')) {
                $namespace_fragment = implode('\\', array_slice(explode('\\', $reflection->getNamespaceName(), 4), 0, 3));
                $service_provider = sprintf('%s\\ServiceProvider', $namespace_fragment);

                $package = $service_provider::getPackageKey();
            }

            $methods = collect($reflection->getMethods(\ReflectionMethod::IS_PUBLIC))->filter(function (&$method) use ($reflection) {
                try {
                    $method->annotation = $this->annotation_reader->getMethodAnnotation($method, AuthorizedRelation::class);

                    return !is_null($method->annotation) && $this->isValidPermissionRelationMethod($reflection, $method);
                } catch (\Throwable $e) {
                    // @todo: nicer handling
                    dd(__METHOD__, $method, $e->getMessage());
                }
            })->each(function($method) use ($permissions, $model, $package) {
                collect($method->annotation->getPolicyAbilities())->each(function($policy_ability) use ($method, $permissions, $model, $package) {
                    $permissions->push([
                        'name' => $this->getPermissionName($model, $policy_ability, $method->getShortName()),
                        'guard' => 'rocXolid',
                        'package' => $package,
                        'controller_class' => null,
                        'model_class' => $model,
                        'attribute' => $method->getName(),
                        'policy_ability_group' => 'model-relation',
                        'policy_ability' => $policy_ability,
                        'scopes' => $method->annotation->getScopes(),
                    ]);
                });
            });
        });

        return $permissions/*->unique(function ($item) {
            return $item['package'].$item['name'];
        })*/;
    }

    private function getPermissionableControllers(string $namespace): Collection
    {
        return $this->package_service->getPackageClasses($namespace, function($class) {
            $reflection = new \ReflectionClass($class);

            return $reflection->implementsInterface(CrudableController::class) && !$reflection->isAbstract();
        });
    }

    private function getPermissionableModels(string $namespace): Collection
    {
        return $this->package_service->getPackageClasses($namespace, function($class) {
            $reflection = new \ReflectionClass($class);

            return $reflection->implementsInterface(Crudable::class) && !$reflection->isAbstract() && !$reflection->isInterface();
        });
    }

    private function isValidPermissionRelationMethod(\ReflectionClass $reflection, \ReflectionMethod $method): bool
    {
        $method_return_type = (string)$method->getReturnType();

        if (blank($method_return_type)) {
            throw new \RuntimeException(sprintf('Method [%s::%s()] has no return type hint', $reflection->getName(), $method->getName()));
        }

        if (!$this->isValidPermissionRelationMethodRelation($method)) {
            throw new \RuntimeException(sprintf(
                'Invalid method [%s::%s()] return type, [%s] expected, [%s] declared',
                $reflection->getName(),
                $method->getName(),
                implode(' or ', [ HasOneOrMany::class, BelongsToMany::class, BelongsTo::class ]),
                $method_return_type
            ));
        }

        if (!$this->isValidPermissionBelongsToRelationMethodRelation($method)) {
            throw new \RuntimeException(sprintf(
                'Invalid method [%s::%s()] policy abilities, [%s] expected, [%s] declared',
                $reflection->getName(),
                $method->getName(),
                'assign',
                json_encode($method->annotation->getPolicyAbilities())
            ));
        }

        if (!$this->isValidPermissionBelongsToManyRelationMethodRelation($method)) {
            throw new \RuntimeException(sprintf(
                'Invalid method [%s::%s()] policy abilities, [%s] expected, [%s] declared',
                $reflection->getName(),
                $method->getName(),
                'assign',
                json_encode($method->annotation->getPolicyAbilities())
            ));
        }

        return true;
    }

    private function isValidPermissionRelationMethodRelation(\ReflectionMethod $method)
    {
        $method_return_type = (string)$method->getReturnType();

        if (in_array($method_return_type, [ HasOneOrMany::class, BelongsToMany::class ])) {
            return true;
        }

        if (in_array($method_return_type, [ BelongsTo::class ])) {
            return true;
        }

        if (is_subclass_of($method_return_type, HasOneOrMany::class) || is_subclass_of($method_return_type, BelongsToMany::class)) {
            return true;
        }

        return false;
    }

    private function isValidPermissionBelongsToRelationMethodRelation(\ReflectionMethod $method)
    {
        $method_return_type = (string)$method->getReturnType();

        if (($method_return_type === BelongsTo::class) && ($method->annotation->getPolicyAbilities() !== [ 'assign' ])) {
            return false;
        }

        if (is_subclass_of($method_return_type, BelongsTo::class) && ($method->annotation->getPolicyAbilities() !== [ 'assign' ])) {
            return false;
        }

        return true;
    }

    private function isValidPermissionBelongsToManyRelationMethodRelation(\ReflectionMethod $method)
    {
        $method_return_type = (string)$method->getReturnType();

        if (($method_return_type === BelongsToMany::class) && ($method->annotation->getPolicyAbilities() !== [ 'assign' ])) {
            return false;
        }

        if (is_subclass_of($method_return_type, BelongsToMany::class) && ($method->annotation->getPolicyAbilities() !== [ 'assign' ])) {
            return false;
        }

        return true;
    }

    private function getPermissionName(string $model, string $policy_ability, string $attribute = null)
    {
        return implode('.', array_filter([
            Str::kebab((new \ReflectionClass($model))->getShortName()),
            $attribute,
            Str::kebab($policy_ability),
        ]));
    }
}
