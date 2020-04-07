<?php

namespace Softworx\RocXolid\Components\Forms;

use App;
use Illuminate\Support\Collection;
// rocXolid contracts
use Softworx\RocXolid\Forms\Contracts\Form as FormContract;
use Softworx\RocXolid\Forms\Contracts\FormField as FormFieldContract;
use Softworx\RocXolid\Components\Contracts\Formable as ComponentFormableContract;
// rocXolid components
use Softworx\RocXolid\Components\AbstractOptionableComponent;

/**
 *
 */
class Form extends AbstractOptionableComponent implements ComponentFormableContract
{
    protected static $field_group_component_class = FormFieldGroup::class;

    protected static $field_component_class = FormField::class;

    protected static $button_toolbar_component_class = FormButtonToolbar::class;

    protected static $button_group_component_class = FormButtonGroup::class;

    protected static $button_component_class = FormButton::class;

    protected $form;

    protected $field_group_components = null;

    protected $field_components = null;

    protected $button_toolbar_components = null;

    protected $button_group_components = null;

    protected $button_components = null;

    public function setForm(FormContract $form): ComponentFormableContract
    {
        $this->form = $form;

        $this->setOptions($this->form->getOption('component'));

        $this
            ->loadFormFieldGroupsComponents()
            ->loadFormFieldsComponents()
            ->organizeFormFieldsComponents();

        $this
            ->loadFormButtonsComponents()
            ->organizeFormButtonsComponents();

        $this->form->setComposed();

        return $this;
    }

    public function getForm(): FormContract
    {
        if (is_null($this->form)) {
            throw new \RuntimeException(sprintf('Form is not set yet to [%s] component', get_class($this)));
        }

        return $this->form;
    }

    public function getFormFieldGroupsComponents(): Collection
    {
        if (is_null($this->field_group_components)) {
            throw new \RuntimeException(sprintf('Form field group components not yet loaded for [%s] component', get_class($this)));
        }

        return $this->field_group_components;
    }

    public function getFormFieldsComponents(): Collection
    {
        if (is_null($this->field_components)) {
            throw new \RuntimeException(sprintf('Form field components not yet loaded for [%s] component', get_class($this)));
        }

        return $this->field_components;
    }

    public function getFormButtonToolbarsComponents(): Collection
    {
        if (is_null($this->button_toolbar_components)) {
            throw new \RuntimeException(sprintf('Form button toolbars components not yet loaded for [%s] component', get_class($this)));
        }

        return $this->button_toolbar_components;
    }

    public function getFormButtonGroupsComponents(): Collection
    {
        if (is_null($this->button_group_components)) {
            throw new \RuntimeException(sprintf('Form button groups components not yet loaded for [%s] component', get_class($this)));
        }

        return $this->button_group_components;
    }

    public function getFormButtonsComponents(): Collection
    {
        if (is_null($this->button_components)) {
            throw new \RuntimeException(sprintf('Form button components not yet loaded for [%s] component', get_class($this)));
        }

        return $this->button_components;
    }

    protected function loadFormFieldGroupsComponents(): ComponentFormableContract
    {
        $this->field_group_components = collect();

        foreach ($this->getForm()->getFormFieldGroups() as $form_field_group) {
            $this->field_group_components->put(
                $form_field_group->getName(),
                $this->buildSubComponent(static::$field_group_component_class)->setFormFieldGroup($form_field_group)
            );
        }

        return $this;
    }

    protected function loadFormFieldsComponents(): ComponentFormableContract
    {
        $this->field_components = collect();

        foreach ($this->getForm()->getFormFields() as $form_field) {
            $this->field_components->put(
                $form_field->getName(),
                $this->buildSubComponent(static::$field_component_class)->setFormField($form_field)
            );
        }

        return $this;
    }

    protected function organizeFormFieldsComponents(): ComponentFormableContract
    {
        foreach ($this->field_components as $name => $field) {
            if ($field->getOption('group', false)) {
                if (isset($this->field_group_components[$field->getOption('group')])) {
                    $this->field_group_components[$field->getOption('group')]->setFormField($field);
                    unset($this->field_components[$name]);
                } else {
                    throw new \InvalidArgumentException(sprintf('Field group [%s] not set in [%s]', $field->getOption('group'), get_class($this)));
                }
            }
        }

        return $this;
    }

    protected function loadFormButtonsComponents(): ComponentFormableContract
    {
        // button toolbars
        $this->button_toolbar_components = collect();

        foreach ($this->getForm()->getButtonToolbars() as $button_toolbar) {
            $this->button_toolbar_components->put(
                $button_toolbar->getName(),
                $this->buildSubComponent(static::$button_toolbar_component_class)->setButtonToolbar($button_toolbar)
            );
        }

        // button groups
        $this->button_group_components = collect();

        foreach ($this->getForm()->getButtonGroups() as $button_group) {
            $this->button_group_components->put(
                $button_group->getName(),
                $this->buildSubComponent(static::$button_group_component_class)->setButtonGroup($button_group)
            );
        }

        // buttons
        $this->button_components = collect();

        foreach ($this->getForm()->getButtons() as $button) {
            $this->button_components->put(
                $button->getName(),
                $this->buildSubComponent(static::$button_component_class)->setButton($button)
            );
        }

        return $this;
    }

    protected function organizeFormButtonsComponents(): ComponentFormableContract
    {
        foreach ($this->button_components as $name => $button) {
            if ($button->getOption('group', false)) {
                if (isset($this->button_group_components[$button->getOption('group')])) {
                    $this->button_group_components[$button->getOption('group')]->addButton($button);
                    unset($this->button_components[$name]);
                } else {
                    throw new \InvalidArgumentException(sprintf('Button group [%s] not set in [%s]', $button->getOption('group'), get_class($this)));
                }
            }
        }

        foreach ($this->button_group_components as $name => $button_group) {
            if ($button_group->getOption('toolbar', false)) {
                if (isset($this->button_toolbar_components[$button_group->getOption('toolbar')])) {
                    $this->button_toolbar_components[$button_group->getOption('toolbar')]->setButtonGroup($button_group);
                    unset($this->button_group_components[$name]);
                } else {
                    throw new \InvalidArgumentException(sprintf('Button toolbar [%s] not set in [%s]', $button_group->getOption('toolbar'), get_class($this)));
                }
            }
        }

        return $this;
    }
}
