<?php

namespace Softworx\RocXolid\Repositories\Support;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\RepositoryBuilder as RepositoryBuilderContract;
use Softworx\RocXolid\Repositories\Contracts\Repository;
// rocXolid controller contracts
use Softworx\RocXolid\Http\Controllers\Contracts\Repositoryable;
// rocXolid repository events
use Softworx\RocXolid\Repositories\Events\AfterRepositoryCreation;

/**
 * Repository builder and dependencies connector.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class RepositoryBuilder implements RepositoryBuilderContract
{
    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;
    /**
     * @var \Softworx\RocXolid\Repositories\Support\RepositoryFilterBuilder
     */
    protected $repository_filter_builder;
    /**
     * @var \Softworx\RocXolid\Repositories\Support\RepositoryColumnBuilder
     */
    protected $repository_column_builder;
    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $event_dispatcher;

    /**
     * Constructor.
     *
     * @param \Illuminate\Contracts\Container\Container $app
     * @param \Softworx\RocXolid\Repositories\Support\RepositoryFilterBuilder $repository_filter_builder
     * @param \Softworx\RocXolid\Repositories\Support\RepositoryColumnBuilder $repository_column_builder
     * @param \Illuminate\Contracts\Events\Dispatcher $event_dispatcher
     */
    public function __construct(Container $app, RepositoryFilterBuilder $repository_filter_builder, RepositoryColumnBuilder $repository_column_builder, EventDispatcher $event_dispatcher)
    {
        $this->app = $app;
        $this->repository_filter_builder = $repository_filter_builder;
        $this->repository_column_builder = $repository_column_builder;
        $this->event_dispatcher = $event_dispatcher;
    }

    /**
     * Get instance of the repository which can be modified.
     *
     * @param \Softworx\RocXolid\Http\Controllers\Contracts\Repositoryable $controller
     * @param string $repository_class
     * @param array $custom_options
     * @return \Softworx\RocXolid\Repositories\Contracts\Repository
     */
    public function buildRepository(Repositoryable $controller, string $repository_class, array $custom_options = []): Repository
    {
        $repository = $this->app->make($this->checkRepositoryClass($repository_class));

        $this
            ->setRepositoryDependencies($repository, $controller)
            ->setRepositoryOptions($repository, $custom_options);

        $repository
            ->buildFilters()
            ->buildColumns()
            ->init();

        $this->event_dispatcher->dispatch(new AfterRepositoryCreation($repository));

        return $repository;
    }

    /**
     * Set the plain repository class.
     *
     * @param string $repository_class
     * @param string $parent_class
     * @return string
     */
    protected function checkRepositoryClass(string $repository_class, string $parent_class = Repository::class): string
    {
        if (!class_exists($repository_class)) {
            throw new \InvalidArgumentException(sprintf('Repository class [%s] does not exist.', $repository_class));
        }

        if (!is_a($repository_class, $parent_class, true)) {
            throw new \InvalidArgumentException(sprintf('Class must be or extend [%s]; [%s] is not.', $parent_class, $repository_class));
        }

        return $repository_class;
    }

    /**
     * Set depedencies on existing repository instance.
     *
     * @param Softworx\RocXolid\Repositories\Contracts\Repository $repository
     * @param \Softworx\RocXolid\Http\Controllers\Contracts\Repositoryable $controller
     * @return Softworx\RocXolid\Repositories\Contracts\RepositoryBuilder
     */
    protected function setRepositoryDependencies(Repository &$repository, Repositoryable $controller): RepositoryBuilderContract
    {
        $repository
            ->setRepositoryBuilder($this)
            ->setRepositoryFilterBuilder($this->repository_filter_builder)
            ->setRepositoryColumnBuilder($this->repository_column_builder)
            ->setEventDispatcher($this->event_dispatcher)
            ->setController($controller)
            ->setRequest($this->app->make('RocXolidRepositoryRequest'));

        return $this;
    }

    /**
     * Set options on existing repository instance.
     *
     * @param Softworx\RocXolid\Repositories\Contracts\Repository $repository
     * @param array $custom_options
     * @return Softworx\RocXolid\Repositories\Contracts\RepositoryBuilder
     */
    protected function setRepositoryOptions(Repository &$repository, array $custom_options = []): RepositoryBuilderContract
    {
        $repository
            ->processRepositoryOptions()
            ->setCustomOptions($custom_options);

        return $this;
    }
}
