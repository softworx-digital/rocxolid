<?php

namespace Softworx\RocXolid\Providers;

use App;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Softworx\RocXolid\Services\PackageService;

/**
 * rocXolid facades service provider.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class FacadeServiceProvider extends IlluminateServiceProvider
{
    /**
     * Register rocXolid facades.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function register()
    {
        $this->app->bind('package.accessor', function () {
            return $this->app->make(PackageService::class);
        });

        return $this;
    }
}
