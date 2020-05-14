<?php

namespace Softworx\RocXolid;

use App;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Filesystem\Filesystem;
// rocXolid provider contracts
use Softworx\RocXolid\Providers\Contracts\RepresentsPackage;

/**
 * General service provider for rocXolid packages.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class AbstractServiceProvider extends IlluminateServiceProvider implements RepresentsPackage
{
    const PACKAGE_CONFIG_FILE = 'composer.json';

    /**
     * {@inheritDoc}
     */
    public static function getPackageKey(): string
    {
        return static::getPackageData()->name;
    }

    /**
     * {@inheritDoc}
     */
    public static function getTitle(): string
    {
        if (!isset(static::getPackageData()->extra)) {
            throw new \UnderflowException(sprintf('Package [%s] extra not set in [%s]', static::class, static::getPackageDataLocation()));
        }

        if (!isset(static::getPackageData()->extra->rocXolid)) {
            throw new \UnderflowException(sprintf('Package [%s] extra.rocXolid not set in [%s]', static::class, static::getPackageDataLocation()));
        }

        if (!isset(static::getPackageData()->extra->rocXolid->title)) {
            throw new \UnderflowException(sprintf('Package [%s] extra.rocXolid.title not set in [%s]', static::class, static::getPackageDataLocation()));
        }

        if (!isset(static::getPackageData()->extra->rocXolid->title->{App::getLocale()})) {
            throw new \UnderflowException(sprintf('Package [%s] extra.rocXolid.title.%s not set in [%s]', static::class, App::getLocale(), static::getPackageDataLocation()));
        }

        return static::getPackageData()->extra->rocXolid->title->{App::getLocale()};
    }

    /**
     * Get package configuration.
     *
     * @return array
     * @throws \stdClass
     */
    private static function getPackageData(): \stdClass
    {
        return json_decode(app(Filesystem::class)->get(static::getPackageDataLocation()));
    }

    /**
     * Get package configuration location.
     *
     * @return string
     */
    private static function getPackageDataLocation(): string
    {
        $reflection = new \ReflectionClass(static::class);

        return sprintf('%s/%s', dirname(dirname($reflection->getFileName())), static::PACKAGE_CONFIG_FILE);
    }
}
