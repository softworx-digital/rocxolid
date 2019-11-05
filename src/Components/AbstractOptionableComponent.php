<?php

namespace Softworx\RocXolid\Components;

use Softworx\RocXolid\Contracts\Optionable;
use Softworx\RocXolid\Traits\Optionable as OptionableTrait;

abstract class AbstractOptionableComponent extends AbstractComponent implements Optionable
{
    use OptionableTrait;

    public function getHtmlAttributes($param = null, $merge = []): string
    {
        $html = '';
        $option = is_null($param) ? 'attributes' : sprintf('%s.attributes', $param);
        $options = array_merge_recursive($this->getOption($option, []), $merge);

        foreach ($options as $attribute => $value) {
            $html .= sprintf('%s="%s" ', $attribute, is_array($value) ? implode(' ', $value) : $value);
        }

        return $html;
    }
}
