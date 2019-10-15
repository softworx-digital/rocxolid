<?php

namespace Softworx\RocXolid\Repositories\Traits;

// contracts
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Repositories\Contracts\RepositoryBuilder;
use Softworx\RocXolid\Repositories\Contracts\RepositoryFilterBuilder;
use Softworx\RocXolid\Repositories\Contracts\RepositoryColumnBuilder;

/**
 *
 */
trait Buildable
{
    /**
     * @var RepositoryBuilder
     */
    private $repository_builder = null;
    /**
     * @var RepositoryFieldBuilder
     */
    private $repository_field_builder = null;
    /**
     * @var RepositoryColumnBuilder
     */
    private $repository_column_builder = null;

    public function buildFilters(): Repository
    {
        $this
            ->getRepositoryFilterBuilder()
                ->addDefinitionFilters($this, [
                    'filters' => $this->getFiltersDefinition()
                ]);

        $this
            ->setFilterValues();

        return $this;
    }

    public function buildColumns(): Repository
    {
        $this
            ->getRepositoryColumnBuilder()
                ->addDefinitionColumns($this, [
                    'columns' => $this->getColumnsDefinition()
                ])
                ->addDefinitionButtons($this, [
                    'buttons' => $this->getButtonsDefinition(),
                ]);

        return $this;
    }

    /**
     * Set the repository builder.
     *
     * @param RepositoryBuilder $repository_builder
     * @return $this
     */
    public function setRepositoryBuilder(RepositoryBuilder $repository_builder): Repository
    {
        $this->repository_builder = $repository_builder;

        return $this;
    }

    /**
      * Get repository builder.
      *
      * @return RepositoryBuilder
      */
    public function getRepositoryBuilder(): RepositoryBuilder
    {
        return $this->repository_builder;
    }

    /**
      * Set the repository filter builder.
      *
      * @param RepositoryFilterBuilder $repository_column_builder
      * @return $this
      */
    public function setRepositoryFilterBuilder(RepositoryFilterBuilder $repository_filter_builder): Repository
    {
        $this->repository_filter_builder = $repository_filter_builder;

        return $this;
    }

    /**
      * Get repository filter builder.
      *
      * @return RepositoryFilterBuilder
      */
    public function getRepositoryFilterBuilder(): RepositoryFilterBuilder
    {
        return $this->repository_filter_builder;
    }

    /**
      * Set the repository column builder.
      *
      * @param RepositoryColumnBuilder $repository_column_builder
      * @return $this
      */
    public function setRepositoryColumnBuilder(RepositoryColumnBuilder $repository_column_builder): Repository
    {
        $this->repository_column_builder = $repository_column_builder;

        return $this;
    }

    /**
     * Get repository column builder.
     *
     * @return RepositoryColumnBuilder
     */
    public function getRepositoryColumnBuilder(): RepositoryColumnBuilder
    {
        return $this->repository_column_builder;
    }
}
