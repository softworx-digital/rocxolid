<?php

namespace Softworx\RocXolid\Tables\Contracts;

/**
 * Represents filter field assigned to a data table.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo: revise & complete
 */
interface Filter
{
    const DATA_PARAM = '_filter';

    /**
     * Retrieve filter name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Retrieve filter field name.
     *
     * @return string
     */
    public function getFieldName(): string;

    /**
     * Retrieve filter type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Retrieve filter value.
     */
    public function getValue();
}
