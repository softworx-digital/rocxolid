<?php

namespace Softworx\RocXolid\Components\Contracts;

use Softworx\RocXolid\Forms\Contracts\FormButtonGroup;
use Softworx\RocXolid\Components\Contracts\FormButtonable;

/**
 * Enables component object to be grouped into button group component and to get button components assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface ButtonGroupable
{
    /**
     * Sets the button group component to the component.
     *
     * @param \Softworx\RocXolid\Forms\Contracts\FormButtonGroup $form_button_group Button group to set the component to.
     * @return \Softworx\RocXolid\Components\Contracts\ButtonGroupable
     */
    public function setButtonGroup(FormButtonGroup $form_button_group): ButtonGroupable;

    /**
     * Gets the button group component the component is set to.
     *
     * @return \Softworx\RocXolid\Forms\Contracts\FormButtonGroup
     */
    public function getButtonGroup(): FormButtonGroup;

    /**
     * Adds a button component to the component.
     *
     * @param \Softworx\RocXolid\Components\Contracts\FormButtonable $button Button to add to the component.
     * @return \Softworx\RocXolid\Components\Contracts\ButtonGroupable
     */
    public function addButton(FormButtonable $button): ButtonGroupable;

    /**
     * Retrieves button components set to the component.
     *
     * @return array
     */
    public function getButtons(): array;
}
