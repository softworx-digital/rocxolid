<?php

namespace Softworx\RocXolid\Repositories\Columns\Type;

// contracts
use Softworx\RocXolid\Repositories\Contracts\Column;
// column types
use Softworx\RocXolid\Repositories\Columns\AbstractColumn;

/**
 *
 */
class Method extends AbstractColumn
{
    protected $default_options = [
        'type-template' => 'method',
        /*
        // field wrapper
        'wrapper' => false,
        // column HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
        */
    ];

    protected function setMethod($method): Column
    {
        if (!method_exists($this->getRepository()->getModel(), $method)) {
            throw new \InvalidArgumentException(sprintf('Class [%s] does not have method [%s]', get_class($this->getRepository()->getModel()), $method));
        }

        if (!(new \ReflectionMethod($this->getRepository()->getModel(), $method))->isPublic()) {
            throw new \InvalidArgumentException(sprintf('Method [%s::%s] is not public', get_class($this->getRepository()->getModel()), $method));
        }

        return $this->setComponentOptions('method', $method);
    }
}
