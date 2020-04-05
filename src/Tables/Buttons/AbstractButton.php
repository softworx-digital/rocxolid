<?php

namespace Softworx\RocXolid\Tables\Buttons;

// rocXolid table contracts
use Softworx\RocXolid\Tables\Buttons\Contracts\Button;
// rocXolid table elements
use Softworx\RocXolid\Tables\AbstractTableElement;
// rocXolid table filter traits
use Softworx\RocXolid\Tables\Buttons\Traits\ComponentOptionsSetter;
// rocXolid table components
use Softworx\RocXolid\Components\Tables\TableButton;

/**
 * Table row button abstraction.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractButton extends AbstractTableElement implements Button
{
    use ComponentOptionsSetter;

    /**
     * Component class definition.
     *
     * @var string
     */
    protected static $component_class = TableButton::class;
}
