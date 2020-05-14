<?php

namespace Softworx\RocXolid\Tables\Filters\Contracts;

// rocXolid contracts
use Softworx\RocXolid\Contracts\RepositoryFilterProvider;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Contracts\Table;

/**
 * Represents filter field assigned to a data table.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo: revise & complete
 */
interface Filter extends RepositoryFilterProvider
{
    const DATA_PARAM = '_filter';

    /**
    * Get component type for table element.
    *
    * @return string
    */
    public function getComponentClass(): string;

    /**
     * Obtain table reference.
     *
     * @return \Softworx\RocXolid\Tables\Contracts\Table
     */
    public function getTable(): Table;

    /**
     * Get filter system name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Obtain filter type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Obtain filter value.
     */
    public function getValue();

    /**
     * Get field name used in the filter form.
     *
     * @return string
     */
    public function getFieldName(): string;
}
