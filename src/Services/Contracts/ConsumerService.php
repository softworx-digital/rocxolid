<?php

namespace Softworx\RocXolid\Services\Contracts;

use Softworx\RocXolid\Services\Contracts\ServiceConsumer;

/**
 * Declares the service requires a consumer.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface ConsumerService
{
    /**
     * Set service consumer reference.
     *
     * @param \Softworx\RocXolid\Services\Contracts\ServiceConsumer $consumer
     * @return \Softworx\RocXolid\Services\Contracts\ConsumerService
     */
    public function setConsumer(ServiceConsumer $consumer): ConsumerService;
}
