<?php

namespace Softworx\RocXolid\Forms\Fields\Traits;

use Illuminate\Support\Str;
use Softworx\RocXolid\Forms\Contracts\FormField;

/**
 * @todo refactor (add type hints & doc)
 */
trait ComponentOptionsSetter
{
    protected function setViewPackage($view_package): FormField
    {
        return $this->setComponentOptions('view-package', $view_package);
    }

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

    protected function setPrefix($prefix): FormField
    {
        return $this->setComponentOptions('prefix', $prefix);
    }

    protected function setUnits($units): FormField
    {
        return $this->setComponentOptions('units', $units);
    }

    protected function setSelectValueChallange($select_value_challange): FormField
    {
        return $this->setComponentOptions('select-value-challange', $select_value_challange);
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
        return $this->setComponentOptions('helper-classes', $classes);
    }

    protected function setChangeAction(string $action): FormField
    {
        // @todo this works only for actions not requiring other params
        return $this->setComponentOptions('attributes', [
            'data-change-action' => $this->getForm()->getController()->getRoute($action, $this->getForm()->getModel())
        ]);
    }

    protected function setDomData($data): FormField
    {
        $dom_data = [];

        foreach ($data as $attribute => $value) {
            $dom_data[sprintf('data-%s', $attribute)] = $this->processDomDataAttributeValues($attribute, $value);
        }

        return $this->setComponentOptions('attributes', $dom_data);
    }

    protected function setComponentOptions($what, $value): FormField
    {
        $method = sprintf('adjust%sComponentOption', Str::studly($what));

        $this->mergeOptions([
            'component' => [
                $what => method_exists($this, $method) ? $this->$method($value) : $value,
            ]
        ]);

        return $this;
    }

    protected function adjustAttributesComponentOption($attributes)
    {
        foreach ($attributes as $attribute => &$value) {
            $method = sprintf('adjust%sComponentAttributeOption', Str::studly($attribute));

            if (method_exists($this, $method)) {
                $value = $this->$method($value);
            }
        }

        return $attributes;
    }
}
