<?php

namespace Softworx\RocXolid\Services;

use Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Doctrine\Common\Annotations\AnnotationReader;
// rocXolid services
use Softworx\RocXolid\Services\PackageService;
// rocXolid annotations
use Softworx\RocXolid\Annotations\AuthorizedAction;
// rocXolid controller contracts
use Softworx\RocXolid\Http\Controllers\Contracts\Permissionable;

/**
 * Service to retrieve rocXolid and App permissions.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class PermissionReaderService
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
                $method->annotation = $this->annotation_reader->getMethodAnnotation($method, AuthorizedAction::class);
                return !is_null($method->annotation);
            })->each(function($method) use ($permissions, $controller, $package) {
                $permissions->push([
                    'name' => $this->getPermissionName($package, $controller, $method),
                    'guard' => 'rocXolid',
                    'package' => $package,
                    'controller_class' => $controller,
                    'policy_ability_group' => $method->annotation->getPolicyAbilityGroup(),
                    'policy_ability' => $method->annotation->getPolicyAbility(),
                    // 'controller_method' => $method->getName(),
                ]);
            });
        });

        return $permissions->unique(function ($item) {
            return $item['package'].$item['name'].$item['guard'].$item['controller_class'].$item['policy_ability_group'].$item['policy_ability'];
        });
    }

    public function persistentPermissions(Model $permission): Collection
    {
        return collect($permission::all()->toArray())->map(function ($item) use ($permission) {
            return collect($item)->only($permission->getFillable())->except('is_enabled')->toArray();
        });
    }

    public function isSynchronized(Collection $code_permissions, Collection $saved_permissions): bool
    {

        return $code_permissions->diffRecords($saved_permissions)->isEmpty()
            && $saved_permissions->diffRecords($code_permissions)->isEmpty();
    }

    private function getPermissionableControllers(string $namespace): Collection
    {
        return $this->package_service->getPackageClasses($namespace, function($class) {
            $reflection = new \ReflectionClass($class);

            return $reflection->implementsInterface(Permissionable::class) && !$reflection->isAbstract();
        });
    }

    private function getPermissionName($package, $controller, $method)
    {
        return sprintf(
            '%s.%s.%s',
            $package,
            Str::kebab((new \ReflectionClass($controller::getModelClass()))->getShortName()),
            is_scalar($method) ? $method : Str::kebab($method->annotation->getPolicyAbility())
        );
    }
}
