<?php

namespace Softworx\RocXolid\Components\Forms;

use App;
use Illuminate\Support\Collection;
// rocXolid contracts
use Softworx\RocXolid\Forms\Contracts\Form as FormContract;
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

    protected function loadFormFieldsComponents(): ComponentFormableContract
    {
        $this->field_group_components = new Collection();
        $this->field_components = new Collection();

        foreach ($this->getForm()->getFormFieldGroups() as $form_field_group) {
            $class = static::$field_group_component_class;

            $this->field_group_components[$form_field_group->getName()] = $class::build()
                ->setTranslationPackage($this->getTranslationPackage())
                ->setTranslationParam($this->getTranslationParam())
                ->setFormFieldGroup($form_field_group);
        }

        foreach ($this->getForm()->getFormFields() as $form_field) {
            $class = static::$field_component_class;

            $this->field_components[$form_field->getName()] = $class::build()
                ->setTranslationPackage($this->getTranslationPackage())
                ->setTranslationParam($this->getTranslationParam())
                ->setFormField($form_field);
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
        $this->button_toolbar_components = new Collection();
        $this->button_group_components = new Collection();
        $this->button_components = new Collection();

        foreach ($this->getForm()->getButtonToolbars() as $button_toolbar) {
            $this->button_toolbar_components[$button_toolbar->getName()] = App::make(static::$button_toolbar_component_class)->setButtonToolbar($button_toolbar);
        }

        foreach ($this->getForm()->getButtonGroups() as $button_group) {
            $this->button_group_components[$button_group->getName()] = App::make(static::$button_group_component_class)->setButtonGroup($button_group);
        }

        foreach ($this->getForm()->getButtons() as $button) {
            $this->button_components[$button->getName()] = App::make(static::$button_component_class)->setButton($button);
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
