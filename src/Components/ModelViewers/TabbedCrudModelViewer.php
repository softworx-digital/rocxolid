<?php

namespace Softworx\RocXolid\Components\ModelViewers;

// rocXolid component contracts
use Softworx\RocXolid\Components\Contracts\Features\Tabbed;
// rocXolid components
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer;

/**
 * Model viewer component with integrated tab support.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class TabbedCrudModelViewer extends CrudModelViewer implements Tabbed
{
    use Traits\HasTabs;
}
