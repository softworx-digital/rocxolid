<?php

namespace Softworx\RocXolid\Components\Exceptions;

use Softworx\RocXolid\Exceptions\Exception;

class InvalidItemImplementationException extends Exception
{
    protected $item;

    protected $interface;

    public function __construct($item, $interface)
    {
        $this->item = $item;
        $this->interface = $interface;

        $this->message = sprintf('Item %s does not implement %s', get_class($this->item), $this->interface);
    }
}
