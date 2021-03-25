<?php

namespace Softworx\RocXolid\Tables\Columns\Traits;

use Softworx\RocXolid\Tables\Columns\Contracts\Column;

trait ComponentOptionsSetter
{
    protected function setViewPackage($view_package): Column
    {
        return $this->setComponentOptions('view-package', $view_package);
    }

    protected function setTemplate($template): Column
    {
        return $this->setComponentOptions('template', $template);
    }

    protected function setTypeTemplate($template): Column
    {
        return $this->setComponentOptions('type-template', $template);
    }

    protected function setArray($is): Column
    {
        return $this->setComponentOptions('array', $is);
    }

    protected function setAttributes($attributes): Column
    {
        return $this->setComponentOptions('attributes', $attributes);
    }

    protected function setWrapper($wrapper): Column
    {
        return $this->setComponentOptions('wrapper', $wrapper);
    }

    protected function setWidth(int $width): Column
    {
        return $this->setComponentOptions('width', $width);
    }

    protected function setPlaceholder($placeholder): Column
    {
        return $this->setComponentOptions('attributes', [ 'placeholder' => $placeholder['title'] ]);
    }

    protected function setOrderable($orderable): Column
    {
        return $this->setComponentOptions('orderable', $orderable);
    }

    protected function setLabel($label): Column
    {
        return $this->setComponentOptions('label', $label);
    }

    protected function setDisabled(): Column
    {
        return $this->setComponentOptions('attributes', [ 'disabled' => 'disabled' ]);
    }

    protected function setEnabled(): Column
    {
        $this->removeOption('component.attribute.disabled');

        return $this;
    }

    protected function setHelperClasses($classes): Column
    {
        $this->setComponentOptions('helper-classes', $classes);

        return $this;
    }

    protected function setDomData($data): Column
    {
        $dom_data = [];

        foreach ($data as $attribute => $value) {
            $dom_data[sprintf('data-%s', $attribute)] = $this->processDomDataAttributeValues($attribute, $value);
        }

        return $this->setComponentOptions('attributes', $dom_data);
    }

    protected function setComponentOptions($what, $value)
    {
        $this->mergeOptions([
            'component' => [
                $what => $value
            ]
        ]);

        return $this;
    }
}
