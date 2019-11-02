<?php

namespace Softworx\RocXolid\Components\Forms;

use Softworx\RocXolid\Forms\Contracts\FormField as FormFieldContract;
use Softworx\RocXolid\Components\AbstractOptionableComponent;
use Softworx\RocXolid\Components\Contracts\FormFieldGroupable as ComponentFormFieldGroupable;
use Softworx\RocXolid\Components\Contracts\FormFieldable as ComponentFormFieldable;

class FormFieldGroup extends AbstractOptionableComponent implements ComponentFormFieldGroupable
{
    // @todo: reason for this?
    protected $form_field;

    protected $form_fields = [];

    public function setFormFieldGroup(FormFieldContract $form_field): ComponentFormFieldGroupable
    {
        $this->form_field = $form_field;

        $this->setOptions($this->form_field->getOption('component'));

        return $this;
    }

    public function getFormFieldGroup(): FormFieldContract
    {
        if (is_null($this->form_field)) {
            throw new \RuntimeException(sprintf('Form field is not set yet to [%s] component', get_class($this)));
        }

        return $this->form_field;
    }

    public function setFormField(ComponentFormFieldable $form_field): ComponentFormFieldGroupable
    {
        $this->form_fields[$form_field->getFormField()->getName()] = $form_field;

        return $this;
    }

    public function getFormFields()
    {
        return $this->form_fields;
    }
}
