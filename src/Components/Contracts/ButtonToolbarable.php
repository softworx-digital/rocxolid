<?php

namespace Softworx\RocXolid\Components\Contracts;

use Softworx\RocXolid\Forms\Contracts\FormField;
use Softworx\RocXolid\Components\Contracts\ButtonGroupable as ComponentButtonGroupable;

/**
 * Enables object to be placed into button toolbar component and to get button groups assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface ButtonToolbarable
{
    /**
     * Sets the button toolbar to the component.
     *
     * @param FormButtonGroup $form_button_group Button group to set the component to.
     * @return ButtonGroupable
     */
    public function setButtonToolbar(FormField $form_field): ButtonToolbarable;

    public function getButtonToolbar();

    public function setButtonGroup(ComponentButtonGroupable $buttongroup): ButtonToolbarable;

    public function getButtonGroups(): array;
}
