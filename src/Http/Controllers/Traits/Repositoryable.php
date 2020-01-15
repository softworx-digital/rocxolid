<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

use App;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Repositories\Contracts\RepositoryBuilder as RepositoryBuilderContract;
// rocXolid repository support
use Softworx\RocXolid\Repositories\Support\RepositoryBuilder;
// rocXolid controller contracts
use Softworx\RocXolid\Http\Controllers\Contracts\Repositoryable as RepositoryableContract;

/**
 * Trait to connect the controller with a repository.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Repositoryable
{
    /**
     * @var array
     */
    protected $repositories = [];

    /**
     * {@inheritDoc}
     * @todo: put this to some kind of (Repository)Service?
     */
    public function createRepository(string $class, string $param = RepositoryableContract::REPOSITORY_PARAM): Repository
    {
        $repository = $this->getRepositoryBuilder()->buildRepository($this, $class);
        $repository->setParam($param);

        return $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function setRepository(Repository $repository, string $param = RepositoryableContract::REPOSITORY_PARAM): RepositoryableContract
    {
        if (isset($this->repositories[$param])) {
            throw new \InvalidArgumentException(sprintf('Repository with given parameter [%s] is already set to [%s]', $param, get_class($this)));
        }

        $this->repositories[$param] = $repository;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getRepositories(): array
    {
        return $this->repositories;
    }

    /**
     * {@inheritDoc}
     */
    public function getRepository(string $param = RepositoryableContract::REPOSITORY_PARAM): Repository
    {
        if (!$this->hasRepositoryAssigned($param)) {
            $class = $this->getRepositoryClass($param);

            if (!class_exists($class)) {
                throw new \InvalidArgumentException(sprintf('Repository class [%s] does not exist.', $class));
            }

            $this->setRepository($this->createRepository($class, $param), $param);
        }

        return $this->repositories[$param];
    }

    /**
     * {@inheritDoc}
     */
    public function hasRepositoryAssigned(string $param = RepositoryableContract::REPOSITORY_PARAM): bool
    {
        return isset($this->repositories[$param]);
    }

    /**
     * {@inheritDoc}
     */
    public function hasRepositoryClass(string $param = RepositoryableContract::REPOSITORY_PARAM): bool
    {
        return class_exists($this->getRepositoryClass($param));
    }

    /**
     * Get repository class to work with according to param.
     *
     * @param string $param
     * @return string
     */
    protected function getRepositoryClass(string $param = RepositoryableContract::REPOSITORY_PARAM): string
    {
        if (isset(static::$repository_param_class) && isset(static::$repository_param_class[$param])) {
            return static::$repository_param_class[$param];
        } elseif (isset(static::$repository_class)) {
            return static::$repository_class;
        }

        throw new \UnderflowException(sprintf('No repository class set for [%s] param [%s].', get_class($this), $param));
    }

    /**
     * Get repository builder.
     *
     * @todo: Subject to change - better use bindings.
     */
    protected function getRepositoryBuilder(): RepositoryBuilderContract
    {
        if (!property_exists($this, 'repository_builder') || is_null($this->repository_builder)) {
            $repository_builder = App::make(RepositoryBuilder::class);

            if (property_exists($this, 'repository_builder')) {
                $this->repository_builder = $repository_builder;
            }
        } elseif (property_exists($this, 'repository_builder')) {
            $repository_builder = $this->repository_builder;
        }

        return $repository_builder;
    }
}
