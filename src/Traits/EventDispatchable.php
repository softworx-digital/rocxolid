<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Contracts\Events\Dispatcher;

trait EventDispatchable
{
    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $event_dispatcher;

    /**
     * {@inheritdoc}
     */
    public function setEventDispatcher(Dispatcher $event_dispatcher)
    {
        $this->event_dispatcher = $event_dispatcher;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEventDispatcher(): Dispatcher
    {
        if (!$this->hasEventDispatcher()) {
            throw new \UnderflowException(sprintf('No event dispatcher set in [%s]', get_class($this)));
        }

        return $this->event_dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function hasEventDispatcher(): bool
    {
        return isset($this->event_dispatcher);
    }
}
