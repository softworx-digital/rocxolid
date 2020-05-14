<?php

namespace Softworx\RocXolid\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

/**
 * Laravel extensions service provider.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class ExtensionServiceProvider extends IlluminateServiceProvider
{
    /**
     * Extend the default request validator.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function boot()
    {
        $this
            ->extendCollections()
            ->extendBlade();

        return $this;
    }

    /**
     * Register macros for collections.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function extendCollections(): IlluminateServiceProvider
    {
        /**
         * Get the diff between two collections of array records.
         */
        Collection::macro('diffRecords', function ($items) {
            return new static($this->filter(function ($item) use ($items){
                return collect($items)->filter(function ($a) use ($item) {
                    return $a === $item;
                })->isEmpty();
            }));
        });

        /**
         * Create associative collection.
         */
        Collection::macro('toAssoc', function () {
            return $this->reduce(function ($assoc, $keyValuePair) {
                list($key, $value) = $keyValuePair;
                $assoc[$key] = $value;
                return $assoc;
            }, new static);
        });

        return $this;
    }

    /**
     * Register Blade directives extensions.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function extendBlade(): IlluminateServiceProvider
    {
        Blade::directive('render', function ($args) {
            $args = sprintf('$component, %s', $args);
            return "<?php \Softworx\RocXolid\Rendering\Services\RenderingService::renderComponent($args); ?>";
        });

        return $this;
    }
}
