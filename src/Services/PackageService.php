<?php

namespace Softworx\RocXolid\Services;

use Str;
use Illuminate\Support\Collection;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Filesystem\Filesystem;
// rocXolid provider contracts
use Softworx\RocXolid\Providers\Contracts\RepresentsPackage;

/**
 * Service to access rocXolid packages.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class PackageService
{
    const CLASS_MAP_FILE = 'vendor/composer/autoload_classmap.php';

    /**
     * Application package manifest reference.
     *
     * @var \Illuminate\Foundation\PackageManifest
     */
    protected $package_manifest;

    /**
     * Application file system reference.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $file_system;

    /**
     * Get rocXolid package service provider based on package key.
     *
     * @return string
     */
    public function get(string $package_key): string
    {
        return $this->rocxolidPackages()->filter(function($package) use ($package_key) {
            return $package::getPackageKey() === $package_key;
        })->first() ?: '';
    }

    /**
     * Constructor.
     *
     * @param \Illuminate\Foundation\PackageManifest $package_manifest Package manifest.
     */
    public function __construct(PackageManifest $package_manifest, Filesystem $file_system)
    {
        $this->package_manifest = $package_manifest;
        $this->file_system = $file_system;
    }

    /**
     * Get all rocXolid packages.
     *
     * @return \Illuminate\Support\Collection
     */
    public function rocxolidPackages(): Collection
    {
        return collect($this->package_manifest->providers())->filter(function($class) {
            $reflection = new \ReflectionClass($class);

            return $reflection->implementsInterface(RepresentsPackage::class);
        });
    }

    /**
     * Get class list based on common namespace and possible filter (for eg. interface implementation).
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPackageClasses(string $namespace, \Closure $filter = null): Collection
    {
        return collect($this->getClassMap())->filter(function($path, $class) use ($namespace, $filter) {
            if (!Str::startsWith($class, $namespace)) {
                return false;
            }

            if ($filter instanceof \Closure) {
                return $filter($class);
            } else {
                return true;
            }
        })->keys();
    }

    /**
     * Get class list registered to the system.
     *
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function getClassMap(): array
    {
        return $this->file_system->getRequire(base_path(static::CLASS_MAP_FILE));
    }
}
