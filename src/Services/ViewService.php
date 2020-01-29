<?php

namespace Softworx\RocXolid\Services;

use View;
use Blade;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\View\View as IlluminateView;
use Illuminate\View\Factory as IlluminateViewFactory;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Symfony\Component\Debug\Exception\FatalThrowableError;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Renderable;
// rocXolid services contracts
use Softworx\RocXolid\Services\Contracts\ViewService as ViewServiceContract;
// rocXolid services exceptions
use Softworx\RocXolid\Services\Exceptions\ViewNotFoundException;

/**
 * Retrieves view for given object and view name.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class ViewService implements ViewServiceContract
{
    /**
     * @param array
     */
    protected static $fallback_view_packages = [
        'rocXolid',
    ];

    /**
     * @param array
     */
    protected static $fallback_view_dirs = [
        '_generic',
    ];

    protected static $not_found_view_path = 'rocXolid::not-found';

    /**
     * Left here for possible experiments...
     * Should represent some config of how to find the view path.
     *
     * @param array
     */
    protected static $preference = [
        'getViewPackages',
        'getViewDirectories',
    ];

    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Constructor.
     *
     * @param \Illuminate\Contracts\Cache\Repository $cache Cache storage.
     */
    public function __construct(CacheRepository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     */
    public static function render(string $content, array $data = []): string
    {
        $data['__env'] = app(IlluminateViewFactory::class);

        $php = Blade::compileString($content);

        $obLevel = ob_get_level();
        ob_start();
        extract($data, EXTR_SKIP);

        try {
            eval('?' . '>' . $php);
        } catch (\Exception $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }
            throw new FatalThrowableError($e);
        }

        return ob_get_clean();
    }

    /**
     * {@inheritDoc}
     */
    public function getView(Renderable $component, string $view_name, array $assignments = []): IlluminateView
    {
        try {
            return View::make($this->getViewPath($component, $view_name), $assignments);
        } catch (\Exception $e) {
            return View::make($this->getNotFoundViewPath(), [ 'e' => $e ]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getViewPath(Renderable $component, string $view_name): string
    {
        $cache_key = $this->getCacheKey($component, $view_name);

        if ($this->cache->has($cache_key)) {
            return $this->cache->get($cache_key);
        }

        $hierarchy = $this->getHierarchy($component);

        $search_paths = collect();

        // looks better than using Collection::each()
        foreach ($this->getViewPackages($component, $hierarchy) as $view_package) {
            foreach ($this->getViewDirectories($component, $hierarchy) as $view_dir) {
                $candidate = $this->composePackageViewPath($view_package, $view_dir, $view_name);
                $search_paths->push($candidate);

                if (View::exists($candidate)) {
                    $this->cache->put($cache_key, $candidate);

                    return $candidate;
                }
            }
        }

        /*
         * this is kinda experimentory, to find out if it is possible to create the iterations dynamically
         * maybe with the use of Collection::pipe()
        collect(static::$preference)->each(function ($method) use ($component, $hierarchy) {
            $this->$method($component, $hierarchy)
            ...
        });
         */

        throw new ViewNotFoundException($component, $view_name, $search_paths);
    }

    /**
     * Get view - placeholder path for not found templates.
     *
     * @return string
     */
    protected function getNotFoundViewPath(): string
    {
        return static::$not_found_view_path;
    }

    /**
     * Create hierarchical collection of components classes for future use.
     * Start with given component and add its eligible parents.
     *
     * @param \Softworx\RocXolid\Contracts\Renderable $component Component at the hierarchy top.
     * @return \Illuminate\Support\Collection
     */
    protected function getHierarchy(Renderable $component): Collection
    {
        $reflection = new \ReflectionClass($component);

        $hierarchy = collect();

        do  {
            $hierarchy->push([
                'class' => $reflection->getName(),
                'dir' => $this->getClassNameViewDirectory($reflection),
            ]);
        } while (
            ($reflection = $reflection->getParentClass())
            && $reflection->isInstantiable()
            && $reflection->implementsInterface(Renderable::class)
        );

        return $hierarchy;
    }

    /**
     * Create priority collection of view packages to look for the view.
     *
     * @param \Softworx\RocXolid\Contracts\Renderable $component Component being rendered.
     * @param \Illuminate\Support\Collection $hierarchy Component hierarchy.
     * @return \Illuminate\Support\Collection
     */
    protected function getViewPackages(Renderable $component, Collection $hierarchy): Collection
    {
        $hierarchy_view_packages = $hierarchy->pluck('class')->map(function($class_name) {
            return app($class_name)->getViewPackage();
        })->toArray();

        return collect(array_merge(
            [ $component->getViewPackage() ],
            $hierarchy_view_packages,
            static::$fallback_view_packages
        ))->unique();
    }

    /**
     * Create priority collection of view directories inside package to look for the view.
     *
     * @param \Softworx\RocXolid\Contracts\Renderable $component Component being rendered.
     * @param \Illuminate\Support\Collection $hierarchy Component hierarchy.
     * @return \Illuminate\Support\Collection
     */
    protected function getViewDirectories(Renderable $component, Collection $hierarchy): Collection
    {
        /*
        $hierarchy_view_dirs = $hierarchy->pluck('class')->map(function($class_name) {
            return app($class_name)->getViewDirectory();
        })->toArray();
        */

        $hierarchy_view_dirs = $hierarchy->pluck('dir')->toArray();

        return collect(array_merge(
            [ $component->getViewDirectory() ],
            $hierarchy_view_dirs,
            static::$fallback_view_dirs
        ))->filter()->unique();
    }

    /**
     * Create directory path based on component's fully qualified name.
     *
     * @param \ReflectionClass $reflection Component's reflection.
     * @return string
     */
    protected function getClassNameViewDirectory(\ReflectionClass $reflection): string
    {
        $path = Str::after($reflection->getName(), 'Components\\');
        $path = collect(explode('\\', $path));

        return $path->map(function($dir) {
            return Str::kebab($dir);
        })->implode('.');
    }

    /**
     * Create full view path based on given package, package subdirectory and view name.
     *
     * @param string $view_package View package to get the view from.
     * @param string $view_dir Directory inside the package.
     * @param string $view_name View name.
     * @return string
     */
    protected function composePackageViewPath(string $view_package, string $view_dir, string $view_name): string
    {
        return sprintf('%s::%s.%s', $view_package, $view_dir, $view_name);
    }

    /**
     * Generate key for caching the view paths.
     *
     * @param \Softworx\RocXolid\Contracts\Renderable $component Component being rendered.
     * @param string $view_name View name.
     * @return string
     */
    protected function getCacheKey(Renderable $component, string $view_name): string
    {
        return sprintf('%s-%s-%s', get_class($component), $component->getViewPackage(), $view_name);
    }
}
