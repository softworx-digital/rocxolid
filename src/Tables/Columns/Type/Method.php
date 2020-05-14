<?php

namespace Softworx\RocXolid\Tables\Columns\Type;

// contracts
use Softworx\RocXolid\Tables\Columns\Contracts\Column;
// column types
use Softworx\RocXolid\Tables\Columns\AbstractColumn;

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
        if (!method_exists($this->getTable()->getController()->getModelType(), $method)) {
            throw new \InvalidArgumentException(sprintf(
                'Class [%s] does not have method [%s]',
                get_class($this->getTable()->getController()->getModelType()),
                $method
            ));
        }

        if (!(new \ReflectionMethod($this->getTable()->getController()->getModelType(), $method))->isPublic()) {
            throw new \InvalidArgumentException(sprintf(
                'Method [%s::%s] is not public',
                get_class($this->getTable()->getController()->getModelType()),
                $method
            ));
        }

        return $this->setComponentOptions('method', $method);
    }
}
