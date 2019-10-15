<?php

namespace Softworx\RocXolid\Contracts;

use Illuminate\Contracts\Events\Dispatcher;

interface EventDispatchable
{
    public function setEventDispatcher(Dispatcher $event_dispatcher);

    public function getEventDispatcher();
}
