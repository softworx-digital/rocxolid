<?php

namespace Softworx\RocXolid\Components\Exceptions;

use Softworx\RocXolid\Exceptions\Exception;

class UndefinedItemException extends Exception
{
    protected $definition;

    public function __construct($definition)
    {
        $this->definition = $definition;

        $this->message = sprintf('Undefined item in %s', print_r($this->definition, true));
    }
}
