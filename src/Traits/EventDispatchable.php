<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Contracts\Events\Dispatcher;

trait EventDispatchable
{
    protected $event_dispatcher;

    public function setEventDispatcher(Dispatcher $event_dispatcher)
    {
        $this->event_dispatcher = $event_dispatcher;

        return $this;
    }

    public function getEventDispatcher()
    {
        return $this->event_dispatcher;
    }
}
