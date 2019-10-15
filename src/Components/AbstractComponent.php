<?php

namespace Softworx\RocXolid\Components;

use Softworx\RocXolid\Helpers\View as ViewHelper;
use Softworx\RocXolid\Components\Contracts\Renderable;

/**
 * Base abstract class for components.
 * Component is an item that can be shown on front end (rendered) with many utility functions around.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractComponent extends AbstractRenderableComponent implements Renderable
{
    protected $dom_id;

    protected $view_package = 'rocXolid';

    protected $view_directory = '';

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

    public function translate($key, $use_repository_param = true)
    {
        return __(sprintf('rocXolid::%s', $this->getTranslationKey($key, $use_repository_param)));
    }

    protected function makeDomId(...$params)
    {
        return ViewHelper::domId($this, $params);
    }

    protected function makeDomIdHash(...$params)
    {
        return ViewHelper::domIdHash($this, $params);
    }

    protected function getTranslationKey($key, $use_repository_param)
    {
        if (!$use_repository_param) {
            return sprintf('general.%s', $key);
        } elseif (method_exists($this, 'getRepository') && $this->getRepository()) {
            return sprintf('%s.%s', $this->getRepository()->getTranslationParam(), $key);
        } else {//if ($this->getController() && $this->getController()->getRepository())
            return '---component--- (' . __METHOD__ . ')';
        }

        return $key;
    }
}
