<?php

namespace Softworx\RocXolid\Services;

use App;
use View;
use Blade;
//use Debugbar;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Illuminate\View\View as IlluminateView;
use Softworx\RocXolid\Services\Contracts\ViewService as ViewServiceContract;
use Softworx\RocXolid\Contracts\Renderable;
use Softworx\RocXolid\Components\Contracts\Controllable;
use Softworx\RocXolid\Components\Contracts\Modellable;
use Softworx\RocXolid\Services\Exceptions\ViewNotFoundException;

class ViewService implements ViewServiceContract
{
    /**
     * Directory name placeholder for general views.
     */
    const GENERIC_VIEW_DIRECTORY = '_generic'; // @todo: put his into config

    protected $namespace_depth = [
        'model' => 'Models',
        'controller' => 'Controllers',
        'component' => 'Components',
    ];

    protected $strip_from_class_name = [
        'model' => 'Model',
        'controller' => 'Controller',
        'component' => 'Component',
    ];

    protected $cache = [];

    /**
     * Returns desired view.
     *
     * @param Renderable $component Component to retrieve view for.
     * @param string $view View name to retrieve.
     * @param array $assignments View variables to assign.
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function getView(Renderable $component, $view_name, $assignments = []): IlluminateView
    {
        //return View::make($this->getViewPath($component, $view_name), $assignments)->render();
        return View::make($this->getViewPath($component, $view_name), $assignments);
    }

    public static function render($string, $data)
    {
        $data['__env'] = app(\Illuminate\View\Factory::class);

        $php = Blade::compileString($string);

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
     * Gets full path for given view.
     *
     * @param Renderable $component Component to retrieve view for.
     * @param string $view View name.
     * @param string $directory_separator Path directory separator.
     * @return string
     */
    public function getViewPath(Renderable $component, $view_name, $directory_separator = '.'): string
    {
        $cache_key = $this->getCacheKey($component, $view_name);

        if (isset($this->cache[$cache_key])) {
            //Debugbar::info('Getting from cache view: ' . $cache_key);

            return $this->cache[$cache_key];
        }

        $hierarchy = $this->getHierarchy($component, $directory_separator);
        $search_paths = [];

        foreach ($hierarchy as $param => $paths) {
            do {
                $component_class_name = key($paths);
                $path = array_shift($paths);

                $current_component = App::make($component_class_name);

                $package_paths = $this->composePackageViewPaths($current_component, $path, $directory_separator, $view_name);
                /**
                 * Old approach, considering only given component's package, could be slower though.
                 */
                //$package_paths = $this->composePackageViewPaths($component, array_shift($paths), $directory_separator, $view_name);

                foreach ($package_paths as $path) {
                    $exists = View::exists($path);

                    if ($exists) {
                        break;
                    }
                }

                $search_paths += $package_paths;
            } while (!$exists && !empty($paths));
        }

        if (!$exists) {
            throw new ViewNotFoundException($component, $view_name, $search_paths);
        }

        //Debugbar::info('Caching view: ' . $cache_key);

        $this->cache[$cache_key] = $path;

        return $path;
    }

    protected function getHierarchy(Renderable $component, $directory_separator): array
    {
        $hierarchy = [];

        /*
        if (($component instanceof Modellable) && is_object($component->getModel()))
        {
            $hierarchy['model'] = $this->getGenericHierarchyNamespacePaths(
                $this->getHierarchyNamespacePaths($component->getModel(), 'model', $directory_separator),
                $directory_separator
            );
        }
        */

        /*
        if (($component instanceof Controllable) && is_object($component->getController()))
        {
            $hierarchy['controller'] = $this->getGenericHierarchyNamespacePaths(
                $this->getHierarchyNamespacePaths($component->getController(), 'controller', $directory_separator),
                $directory_separator
            );
        }
        */

        /*
        $hierarchy['component'] = $this->getGenericHierarchyNamespacePaths(
            $this->getHierarchyNamespacePaths($component, 'component', $directory_separator),
            $directory_separator
        );
        */

        $hierarchy['component'] = $this->getHierarchyNamespacePaths($component, 'component', $directory_separator);

        return $hierarchy;
    }

    /**
     * Creates hierarchical directory structures based on controller's
     * parent hierarchy and namespace to be used in search for a view.
     *
     * @param string $directory_separator Path directory separator.
     * @return array
     */
    protected function getHierarchyNamespacePaths($object, $param, $directory_separator): array
    {
        $reflection = new \ReflectionClass($object);
        $hierarchy = [];
        $hierarchy[$reflection->getName()] = $reflection;
        $generic = [];

        while (($parent = $reflection->getParentClass())
            && $parent->isInstantiable()
            //&& (strpos($reflection->getNamespaceName(), $parent->getNamespaceName()) !== false)) // allow mixing up totally different packages
            && (strpos($parent->getNamespaceName(), $this->namespace_depth[$param]) !== false)
            && ($interfaces = class_implements($parent->getName()))
            && (($param != 'component') || in_array(Renderable::class, $interfaces))) {
            $reflection = $parent;

            $hierarchy[$parent->getName()] = $reflection;
        }

        return array_map(
            function ($reflection) use ($param, $directory_separator) {
                //$dir = str_replace($this->strip_from_class_name[$param], '', $reflection->getShortName());
                $dir = $reflection->getShortName();
                $dir = preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $dir); // CamelCase to camel-case

                $path = substr($reflection->getNamespaceName(), strpos($reflection->getNamespaceName(), $this->namespace_depth[$param]) + strlen($this->namespace_depth[$param]));
                $path = preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $path); // CamelCase to camel-case
                $path = explode('\\', $path);

                $interfaces = class_implements($reflection->getName());

                if (empty($interfaces) || !in_array(Renderable::class, $interfaces)) {
                    $path[] = $param;
                }

                $path[] = $dir;

                return strtolower(implode($directory_separator, array_filter($path)));
            },
            $hierarchy
        );
    }

    protected function getGenericHierarchyNamespacePaths($hierarchy, $directory_separator): array
    {
        $paths = $hierarchy;

        array_map(
            function ($path, $i) use (&$paths, $directory_separator) {
                array_splice($paths, ($i * 2) + 1, 0, sprintf('%s%s%s', $path, $directory_separator, self::GENERIC_VIEW_DIRECTORY));
            },
            $hierarchy,
            array_keys($hierarchy)
        );

        return $paths;
    }

    protected function composePackageViewPaths(Renderable $component, $path, $directory_separator, $view_name): array
    {
        $view_path = implode($directory_separator, array_filter([
            $component->getViewDirectory(),
            $path,
            $view_name
        ]));

        if ($component->hasViewPackage()) {
            $package_view_paths = [];

            foreach ($component->getViewPackages() as $package) {
                $package_view_paths[] = sprintf('%s::%s', $package, $view_path);
            }

            return $package_view_paths;
        } else {
            return (array)$view_path;
        }
    }

    protected function getCacheKey(Renderable $component, $view_name): string
    {
        return sprintf('%s-%s', get_class($component), $view_name);
    }
}
