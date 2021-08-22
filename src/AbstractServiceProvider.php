<?php

namespace Softworx\RocXolid;

use App;
use Illuminate\Support\Str;
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
    /**
     * Package config file name.
     *
     * @var string
     */
    const PACKAGE_CONFIG_FILE = 'composer.json';

    /**
     * {@inheritDoc}
     */
    public static function getPackageKey(bool $with_vendor = true): string
    {
        return $with_vendor ? static::packageData()->name : Str::after(static::packageData()->name, '/');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTitle(): string
    {
        if (!isset(static::packageData()->extra)) {
            throw new \UnderflowException(sprintf('Package [%s] extra not set in [%s]', static::class, static::packageDataPath()));
        }

        if (!isset(static::packageData()->extra->rocXolid)) {
            throw new \UnderflowException(sprintf('Package [%s] extra.rocXolid not set in [%s]', static::class, static::packageDataPath()));
        }

        if (!isset(static::packageData()->extra->rocXolid->title)) {
            throw new \UnderflowException(sprintf('Package [%s] extra.rocXolid.title not set in [%s]', static::class, static::packageDataPath()));
        }

        if (!isset(static::packageData()->extra->rocXolid->title->{App::getLocale()})) {
            throw new \UnderflowException(sprintf('Package [%s] extra.rocXolid.title.%s not set in [%s]', static::class, App::getLocale(), static::packageDataPath()));
        }

        return static::packageData()->extra->rocXolid->title->{App::getLocale()};
    }

    /**
     * Expose package's publishable files.
     *
     * @return \Softworx\RocXolid\AbstractServiceProvider
     */
    protected function publish(): AbstractServiceProvider
    {
        $root_path = $this->packageRootPath();

        // config files
        // php artisan vendor:publish --provider="Softworx\RocXolid\<package>?\ServiceProvider" --tag="config" (--force to overwrite)
        $this->publishes([
            $this->configSourcePath($root_path) => $this->configPublishPath(),
        ], 'config');

        // lang files
        // php artisan vendor:publish --provider="Softworx\RocXolid\CMS\Elements\ServiceProvider" --tag="lang" (--force to overwrite)
        $this->publishes([
            $this->translationsSourcePath($root_path) => $this->translationsPublishPath(),
        ], 'lang');

        // views files
        // php artisan vendor:publish --provider="Softworx\RocXolid\CMS\Elements\ServiceProvider" --tag="views" (--force to overwrite)
        $this->publishes([
            $this->viewsSourcePath($root_path) => $this->viewsPublishPath(),
        ], 'views');

        // assets files
        // php artisan vendor:publish --provider="Softworx\RocXolid\CMS\Elements\ServiceProvider" --tag="assets" (--force to overwrite)
        $this->publishes([
            $this->assetsSourcePath($root_path) => $this->assetsPublishPath(),
        ], 'assets');

        // migrations
        // php artisan vendor:publish --provider="Softworx\RocXolid\CMS\Elements\ServiceProvider" --tag="migrations" (--force to overwrite)
        $this->publishes([
            $this->migrationsSourcePath($root_path) => $this->migrationsPublishPath(),
        ], 'migrations');

        // db dumps
        // php artisan vendor:publish --provider="Softworx\RocXolid\CMS\Elements\ServiceProvider" --tag="dumps" (--force to overwrite)
        $this->publishes([
            $this->dumpsSourcePath($root_path) => $this->dumpsPublishPath(),
        ], 'dumps');

        return $this;
    }

    /**
     * Obtatin package's config files path in target app.
     *
     * @return string
     */
    public static function configSourcePath(string $root_path = ''): string
    {
        return sprintf('%s/config', $root_path);
    }

    /**
     * Obtatin package's lang files path in target app.
     *
     * @return string
     */
    public static function translationsSourcePath(string $root_path = ''): string
    {
        return sprintf('%s/resources/lang', $root_path);
    }

    /**
     * Obtatin package's views files path in target app.
     *
     * @return string
     */
    public static function viewsSourcePath(string $root_path = ''): string
    {
        return sprintf('%s/resources/views', $root_path);
    }

    /**
     * Obtatin package's assets files path in target app.
     *
     * @return string
     */
    public static function assetsSourcePath(string $root_path = ''): string
    {
        return sprintf('%s/resources/assets', $root_path);
    }

    /**
     * Obtatin package's database migrations files path in target app.
     *
     * @return string
     */
    public static function migrationsSourcePath(string $root_path = ''): string
    {
        return sprintf('%s/database/migrations', $root_path);
    }

    /**
     * Obtatin package's database dumps files path in target app.
     *
     * @return string
     */
    public static function dumpsSourcePath(string $root_path = ''): string
    {
        return sprintf('%s/database/dumps', $root_path);
    }

    /**
     * Obtatin package's config files path in target app.
     *
     * @return string
     */
    public static function configPublishPath(): string
    {
        return config_path(Str::replaceFirst('rocxolid', 'rocXolid', Str::replaceFirst('-', '/', static::getPackageKey(false))));
    }

    /**
     * Obtatin package's lang files path in target app.
     *
     * @return string
     */
    public static function translationsPublishPath(): string
    {
        return resource_path(sprintf('lang/vendor/%s', static::getPackageKey(false))); // omit vendor name in path
    }

    /**
     * Obtatin package's views files path in target app.
     *
     * @return string
     */
    public static function viewsPublishPath(): string
    {
        return resource_path(sprintf('views/vendor/%s', static::getPackageKey()));
    }

    /**
     * Obtatin package's assets files path in target app.
     *
     * @return string
     */
    public static function assetsPublishPath(): string
    {
        return public_path(sprintf('vendor/%s', static::getPackageKey()));
    }

    /**
     * Obtatin package's database migrations files path in target app.
     *
     * @return string
     */
    public static function migrationsPublishPath(): string
    {
        return database_path(sprintf('migrations/vendor/%s', static::getPackageKey()));
    }

    /**
     * Obtatin package's database dumps files path in target app.
     *
     * @return string
     */
    public static function dumpsPublishPath(): string
    {
        return database_path(sprintf('dumps/vendor/%s', static::getPackageKey()));
    }

    /**
     * Get package configuration.
     *
     * @return array
     * @throws \stdClass
     */
    private static function packageData(): \stdClass
    {
        return json_decode(app(Filesystem::class)->get(static::packageDataPath()));
    }

    /**
     * Get package root path.
     *
     * @return string
     */
    private static function packageRootPath(): string
    {
        $reflection = new \ReflectionClass(static::class);

        return dirname(dirname($reflection->getFileName()));
    }

    /**
     * Get package configuration path.
     *
     * @return string
     */
    private static function packageDataPath(): string
    {
        return static::packageRootPath() . '/' . static::PACKAGE_CONFIG_FILE;
    }
}
