<?php

namespace Softworx\RocXolid\Http\Controllers;

// rocXolid controllers
use Softworx\RocXolid\Http\Controllers\AbstractCrudController;

/**
 * Utility rocXolid controller for CRUD (and associated) operations associated to sections.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractCrudControllerWithSectionResponse extends AbstractCrudController
{
    use Traits\Utils\HasSectionResponse;
}
