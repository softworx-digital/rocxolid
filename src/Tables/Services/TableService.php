<?php

namespace Softworx\RocXolid\Tables\Services;

// rocXolid service contracts
use Softworx\RocXolid\Services\Contracts\ConsumerService;
// rocXolid service contracts
use Softworx\RocXolid\Contracts\ServiceConsumer;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Services\Contracts\TableService as TableServiceContract;
use Softworx\RocXolid\Tables\Builders\Contracts\TableBuilder;
use Softworx\RocXolid\Tables\Contracts\Tableable;
use Softworx\RocXolid\Tables\Contracts\Table;

/**
 * Service to retrieve and manipulate tables.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class TableService implements TableServiceContract
{
    /**
     * Service consumer reference.
     *
     * @var \Softworx\RocXolid\Tables\Contracts\Tableable
     */
    protected $consumer;

    /**
     * Table builder reference.
     *
     * @var \Softworx\RocXolid\Tables\Contracts\TableBuilder
     */
    protected $table_builder;

    /**
     * Constructor.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\TableBuilder
     * @return \Softworx\RocXolid\Tables\Services\TableService
     */
    public function __construct(TableBuilder $table_builder)
    {
        $this->table_builder = $table_builder;
    }

    /**
     * {@inheritDoc}
     */
    public function setConsumer(ServiceConsumer $consumer): ConsumerService
    {
        // cannot do this more clean coz TableService implements ConsumerService too
        // and ConsumerService requires ServiceConsumer for setConsumer
        // extending ServiceConsumer with Tableable and setting Tableable arguments doesn't work
        if (!($consumer instanceof Tableable)) {
            throw new \InvalidArgumentException(sprintf('Provided service consumer [%s] must implement [%s] interface', get_class($consumer), Tableable::class));
        }

        $this->consumer = $consumer;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function createTable(string $param, ?string $type = null): Table
    {
        $type = $type ?? $this->consumer->getTableMappingType($param);

        return $this->table_builder->buildTable($this->consumer, $type, $param);
    }
}
