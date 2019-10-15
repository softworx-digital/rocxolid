<?php

namespace Softworx\RocXolid\Repositories\Traits;

use App;
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Repositories\Contracts\Repositoryable as RepositoryableContract;
use Softworx\RocXolid\Repositories\Support\RepositoryBuilder;

// @todo - asi do RepositoryService pichnut
trait Repositoryable
{
    protected $repositories;

    //protected $repository_bulider;

    public function createRepository($class, $param = RepositoryableContract::REPOSITORY_PARAM): Repository
    {
        $repository = $this->getRepositoryBuilder()->buildRepository($class, $this);
        $repository->setParam($param);

        return $repository;
    }

    public function setRepository(Repository $repository, $param = RepositoryableContract::REPOSITORY_PARAM): RepositoryableContract
    {
        if (isset($this->repositories[$param])) {
            throw new \InvalidArgumentException(sprintf('Repository with given parameter [%s] is already set to [%s]', $param, get_class($this)));
        }

        $this->repositories[$param] = $repository;

        return $this;
    }

    public function getRepositories()
    {
        return $this->repositories;
    }

    public function getRepository($param = RepositoryableContract::REPOSITORY_PARAM): Repository
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

    public function hasRepositoryAssigned($param = RepositoryableContract::REPOSITORY_PARAM)
    {
        return isset($this->repositories[$param]);
    }

    public function hasRepositoryClass($param = RepositoryableContract::REPOSITORY_PARAM)
    {
        return class_exists($this->getRepositoryClass($param));
    }

    public function getRepositoryClass($param = RepositoryableContract::REPOSITORY_PARAM): string
    {
        if (isset(static::$repository_param_class) && isset(static::$repository_param_class[$param])) {
            return static::$repository_param_class[$param];
        } elseif (isset(static::$repository_class)) {
            return static::$repository_class;
        } else {
            $repository_class = str_replace('-', '', ucwords($param, '-')); // dash-separated to DashSeparated
            $reflection = new \ReflectionClass($this->getRepositoryElementClass());

            $class = sprintf('%s\Repositories\%s\%s', $reflection->getNamespaceName(), $reflection->getShortName(), $form_class);

            return $class;
        }
    }

    protected function getRepositoryBuilder()
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

    protected function getRepositoryElementClass()
    {
        return $this;
    }
}
