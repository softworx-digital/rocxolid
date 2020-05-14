<?php

namespace Softworx\RocXolid\Services\Traits;

// rocXolid contracts
use Softworx\RocXolid\Services\Contracts\ServiceConsumer;
// rocXolid service contracts
use Softworx\RocXolid\Services\Contracts\ConsumerService;

/**
 * Trait to set service consumer.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait HasServiceConsumer
{
    /**
     * Service consumer reference.
     *
     * @var \Softworx\RocXolid\Services\Contracts\ServiceConsumer
     */
    protected $consumer;

    /**
     * {@inheritDoc}
     */
    public function setConsumer(ServiceConsumer $consumer): ConsumerService
    {
        if (!$this->validateServiceConsumer($consumer)) {
            $this->onInvalidServiceConsumer($consumer);
        }

        $this->consumer = $consumer;

        return $this;
    }

    /**
     * Check if proper service consumer provided.
     *
     * @param \Softworx\RocXolid\Services\Contracts\ServiceConsumer $consumer
     * @return bool
     */
    protected function validateServiceConsumer(ServiceConsumer $consumer): bool
    {
        return true;
    }

    /**
     * Action to take if invalid service consumer provided.
     *
     * @param \Softworx\RocXolid\Services\Contracts\ServiceConsumer $consumer
     * @throws \InvalidArgumentException
     */
    protected function onInvalidServiceConsumer(ServiceConsumer $consumer)
    {
        throw new \InvalidArgumentException(sprintf('Provided service consumer [%s] is invalid', get_class($consumer)));
    }
}
