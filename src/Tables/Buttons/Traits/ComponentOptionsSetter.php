<?php

namespace Softworx\RocXolid\Tables\Buttons\Traits;

use Softworx\RocXolid\Tables\Buttons\Contracts\Button;

trait ComponentOptionsSetter
{
    protected function setViewPackage($view_package): Button
    {
        return $this->setComponentOptions('view-package', $view_package);
    }

    protected function setTemplate($template): Button
    {
        return $this->setComponentOptions('template', $template);
    }

    protected function setTypeTemplate($template): Button
    {
        return $this->setComponentOptions('type-template', $template);
    }

    protected function setArray($is): Button
    {
        return $this->setComponentOptions('array', $is);
    }

    protected function setAttributes($attributes): Button
    {
        return $this->setComponentOptions('attributes', $attributes);
    }

    protected function setWrapper($wrapper): Button
    {
        return $this->setComponentOptions('wrapper', $wrapper);
    }

    protected function setWidth(int $width): Button
    {
        return $this->setComponentOptions('width', $width);
    }

    protected function setPlaceholder($placeholder): Button
    {
        return $this->setComponentOptions('attributes', [ 'placeholder' => $placeholder['title'] ]);
    }

    protected function setOrderable($orderable): Button
    {
        return $this->setComponentOptions('orderable', $orderable);
    }

    protected function setLabel($label): Button
    {
        return $this->setComponentOptions('label', $label);
    }

    protected function setDisabled(): Button
    {
        return $this->setComponentOptions('attributes', [ 'disabled' => 'disabled' ]);
    }

    protected function setEnabled(): Button
    {
        $this->removeOption('component.attribute.disabled');

        return $this;
    }

    protected function setHelperClasses($classes): Button
    {
        $this->setComponentOptions('helper-classes', $classes);

        return $this;
    }

    protected function setDomData($data): Button
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
