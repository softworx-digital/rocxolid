<?php

namespace Softworx\RocXolid\Components\Contracts\Componentable;

use Softworx\RocXolid\Components\Contracts\Formable;

/**
 * Allow formable component to be added.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Form
{
    /**
     * Set formable component.
     *
     * @param \Softworx\RocXolid\Components\Contracts\Formable $component
     * @return \Softworx\RocXolid\Components\Contracts\Componentable\Form
     */
    public function setFormComponent(Formable $component): Form;

    /**
     * Get formable component.
     *
     * @return \Softworx\RocXolid\Components\Contracts\Formable
     */
    public function getFormComponent(): Formable;
}
