<?php

namespace Softworx\RocXolid\Models\Traits;

use Softworx\RocXolid\Forms\Contracts\FormField;

/**
 * Trait to satisfy field item usage.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait CanBeFieldItem
{
    /**
     * Initialize model for form field item use.
     *
     * @param \Softworx\RocXolid\Forms\Contracts\FormField $form_field
     */
    public function initAsFieldItem(FormField $form_field)
    {
        return $this;
    }
}
