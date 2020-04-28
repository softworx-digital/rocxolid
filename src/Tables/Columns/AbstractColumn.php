<?php

namespace Softworx\RocXolid\Tables\Columns;

// rocXolid table contracts
use Softworx\RocXolid\Tables\Columns\Contracts\Column;
// rocXolid table elements
use Softworx\RocXolid\Tables\AbstractTableElement;
// rocXolid table column traits
use Softworx\RocXolid\Tables\Columns\Traits\ComponentOptionsSetter;
// rocXolid table components
use Softworx\RocXolid\Components\Tables\TableColumn;

/**
 * Table column abstraction.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractColumn extends AbstractTableElement implements Column
{
    use ComponentOptionsSetter;

    /**
     * Component class definition.
     *
     * @var string
     */
    protected static $component_class = TableColumn::class;

    /**
     * Obtain value to be used in the view.
     *
     * @param mixed $value
     * @return mixed
     */
    public function getModelAttributeViewValue($value)
    {
        return $value;
    }
}
