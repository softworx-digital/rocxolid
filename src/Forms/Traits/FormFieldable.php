<?php

namespace Softworx\RocXolid\Forms\Traits;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Forms\Contracts\FormFieldable as FormFieldableContract;
use Softworx\RocXolid\Forms\Contracts\FormField;

trait FormFieldable
{
    private $_form_field_groups = null;

    private $_form_fields = null;

    public function addFormField(FormField $form_field): FormFieldableContract
    {
        $this->getFormFields()->put($form_field->getName(), $form_field);

        return $this;
    }

    public function hasFormField($field): bool
    {
        return $this->getFormFields()->has($field);
    }

    public function getFormField($field): FormField
    {
        if ($this->hasFormField($field)) {
            return $this->getFormFields()->get($field);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid field (name) [%s] requested in [%s]', $field, get_class($this)));
        }
    }

    public function destoyFormField($field): FormFieldableContract
    {
        if ($this->hasFormField($field)) {
            $this->getFormFields()->forget($field);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid field (name) [%s] requested in [%s]', $field, get_class($this)));
        }

        return $this;
    }

    public function setFormFieldGroups($form_field_groups): FormFieldableContract
    {
        $this->_form_field_groups = collect($form_field_groups);

        return $this;
    }

    public function getFormFieldGroups(): Collection
    {
        if (is_null($this->_form_field_groups)) {
            $this->_form_field_groups = collect();
        }

        return $this->_form_field_groups;
    }

    public function getFormFieldGroup($name): FormField
    {
        if ($this->getFormFieldGroups()->has($name)) {
            return $this->getFormFieldGroups()->get($name);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid field group (name) [%s] requested', $name));
        }
    }

    public function destoyFormFieldGroup($name): FormFieldableContract
    {
        if ($this->getFormFieldGroups()->has($name)) {
            $this->getFormFields($name)->each(function ($field, $key) {
                $this->destoyFormField($key);
            });
            $this->getFormFieldGroups()->forget($name);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid field group (name) [%s] requested', $name));
        }

        return $this;
    }

    public function setFormFields($form_fields): FormFieldableContract
    {
        $this->_form_fields = collect($form_fields);

        return $this;
    }
    //////////////////////////// @todo subject to change
    public function getFormFields($group_name = null): Collection
    {
        if (is_null($this->_form_fields)) {
            $this->_form_fields = collect();
        }

        if (!is_null($group_name)) {
            $form_fields = $this->_form_fields->filter(function ($form_field, $key) use ($group_name) {
                return $form_field->hasOption('component.group') && ($form_field->getOption('component.group') === $group_name);
            });
        } else {
            $form_fields = $this->_form_fields;
        }

        return $form_fields;
    }

    public function reorderFormFields(array $order_definition): FormFieldableContract
    {
        if (blank($order_definition)) {
            return $this;
        }

        $fields = $this->getFormFields()->sortBy(function ($form_field, $name) use ($order_definition) {
            return in_array($name, $order_definition) ? array_search($name, $order_definition) : array_search($name, $this->getFormFields()->keys()->all()) + count($order_definition);
        });

        return $this->setFormFields($fields);
    }

    public function getFormFieldsValues(): Collection
    {
        $form_fields_values = collect();

        foreach ($this->getFormFields() as $form_field) {
            if (empty($form_field->getValue()) && ($form_field->getValue() !== '0')) {
                $form_fields_values->put($form_field->getName(), null);
            } else {
                $form_fields_values->put($form_field->getName(), $form_field->getFinalValue());
            }
        }

        return $form_fields_values;
    }

    public function clearFormFieldsValues(): FormFieldableContract
    {
        foreach ($this->getFormFields() as $form_field) {
            $form_field->setValue(null);
        }

        return $this;
    }
}
