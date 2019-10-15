<?php

namespace Softworx\RocXolid\Http\Controllers;

use Illuminate\Routing\Controller as IlluminateController;
use Softworx\RocXolid\Http\Controllers\Traits\Routable as RoutableTrait;

abstract class AbstractController extends IlluminateController
{
    use RoutableTrait;
}
