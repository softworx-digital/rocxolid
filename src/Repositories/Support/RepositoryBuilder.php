<?php

namespace Softworx\RocXolid\Repositories\Support;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Softworx\RocXolid\Repositories\Contracts\RepositoryBuilder as RepositoryBuilderContract;
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Repositories\Contracts\Repositoryable;
use Softworx\RocXolid\Repositories\Events\AfterRepositoryCreation;

class RepositoryBuilder implements RepositoryBuilderContract
{
    /**
     * @var Container
     */
    protected $app;
    /**
     * @var RepositoryFilterBuilder
     */
    protected $repository_filter_builder;
    /**
     * @var RepositoryColumnBuilder
     */
    protected $repository_column_builder;
    /**
     * @var EventDispatcher
     */
    protected $event_dispatcher;

    /**
     * Constructor.
     *
     * @param Container  $app
     * @param RepositoryFilterBuilder $repository_filter_builder
     * @param RepositoryColumnBuilder $repository_column_builder
     * @param EventDispatcher $event_dispatcher
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
     * @param string $repository_class
     * @param array $custom_options
     * @return \Softworx\RocXolid\Repositories\Contracts\Repository
     */
    public function buildRepository($repository_class, Repositoryable $repository_holder, array $custom_options = []): Repository
    {
        $repository = $this->app->make($this->checkRepositoryClass($repository_class));

        $this
            ->setRepositoryDependencies($repository, $repository_holder)
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
     * @param string $class
     */
    protected function checkRepositoryClass($repository_class, $parent_class = Repository::class): string
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
     * @param Repository $repository
     * @return RepositoryBuilderContract
     */
    protected function setRepositoryDependencies(Repository &$repository, Repositoryable $repository_holder): RepositoryBuilderContract
    {
        $repository
            ->setRepositoryBuilder($this)
            ->setRepositoryFilterBuilder($this->repository_filter_builder)
            ->setRepositoryColumnBuilder($this->repository_column_builder)
            ->setEventDispatcher($this->event_dispatcher)
            ->setController($repository_holder)
            ->setRequest($this->app->make('RocXolidRepositoryRequest'));

        return $this;
    }

    /**
     * Set options on existing repository instance.
     *
     * @param Repository $repository
     * @param array $custom_options
     * @return RepositoryBuilderContract
     */
    protected function setRepositoryOptions(Repository &$repository, array $custom_options = []): RepositoryBuilderContract
    {
        $repository
            ->processRepositoryOptions()
            ->setCustomOptions($custom_options);

        return $this;
    }
}
