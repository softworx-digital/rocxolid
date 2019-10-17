<?php

namespace Softworx\RocXolid\Components;

use Softworx\RocXolid\Helpers\View as ViewHelper;
use Softworx\RocXolid\Contracts\Translatable;
use Softworx\RocXolid\Contracts\Renderable;
use Softworx\RocXolid\Traits\Renderable as RenderableTrait;
use Softworx\RocXolid\Traits\Translatable as TranslatableTrait;

/**
 * Base abstract class for components.
 * Component is an item that can be shown on front end (rendered) with many utility functions around.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractComponent implements Renderable, Translatable
{
    use RenderableTrait;
    use TranslatableTrait;

    const DEFAULT_TEMPLATE_NAME = 'default';

    protected $dom_id;

    protected $view_package = 'rocXolid';

    protected $view_directory = '';

    protected $translation_package = 'rocXolid';

    public function setDomId($id)
    {
        $this->dom_id = $id;

        return $this;
    }

    public function getDomId(...$params)
    {
        if (!isset($this->dom_id)) {
            $this->setDomId($this->makeDomId($params));
        }

        return $id;
    }

    protected function getDomIdHash(...$params)
    {
        return ViewHelper::domIdHash($this, $params);
    }

    protected function makeDomId(...$params)
    {
        return ViewHelper::domId($this, $params);
    }

    protected function makeDomIdHash(...$params)
    {
        return ViewHelper::domIdHash($this, $params);
    }
}
