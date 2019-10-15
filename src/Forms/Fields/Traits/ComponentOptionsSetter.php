<?php

namespace Softworx\RocXolid\Forms\Fields\Traits;

use Softworx\RocXolid\Forms\Contracts\FormField;

trait ComponentOptionsSetter
{
    protected function setTemplate($template): FormField
    {
        return $this->setComponentOptions('template', $template);
    }

    protected function setTypeTemplate($template): FormField
    {
        return $this->setComponentOptions('type-template', $template);
    }

    protected function setGroup($group): FormField
    {
        return $this->setComponentOptions('group', $group);
    }

    protected function setArray($is): FormField
    {
        return $this->setComponentOptions('array', $is);
    }

    protected function setAttributes($attributes): FormField
    {
        return $this->setComponentOptions('attributes', $attributes);
    }

    protected function setWrapper($wrapper): FormField
    {
        return $this->setComponentOptions('wrapper', $wrapper);
    }

    protected function setPlaceholder($placeholder): FormField
    {
        return $this->setComponentOptions('attributes', [ 'placeholder' => $placeholder['title'] ]);
    }

    protected function setLabel($label): FormField
    {
        return $this->setComponentOptions('label', $label);
    }

    protected function setDisabled(): FormField
    {
        return $this->setComponentOptions('attributes', [ 'disabled' => 'disabled' ]);
    }

    protected function setEnabled(): FormField
    {
        $this->removeOption('component.attribute.disabled');

        return $this;
    }

    protected function setHelperClasses($classes): FormField
    {
        $this->setComponentOptions('helper-classes', $classes);

        return $this;
    }

    protected function setDomData($data): FormField
    {
        $dom_data = [];

        foreach ($data as $attribute => $value) {
            $dom_data[sprintf('data-%s', $attribute)] = $this->processDomDataAttributeValuess($attribute, $value);
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
