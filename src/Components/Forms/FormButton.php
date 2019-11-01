<?php

namespace Softworx\RocXolid\Components\Forms;

use Softworx\RocXolid\Components\General\Button;
use Softworx\RocXolid\Components\Contracts\FormButtonable as ComponentFormButtonable;
use Softworx\RocXolid\Forms\Contracts\FormField as FormFieldContract;

class FormButton extends Button implements ComponentFormButtonable
{
    protected $form_field;

    public function setButton(FormFieldContract $form_field): ComponentFormButtonable
    {
        $this->form_field = $form_field;

        $this->setOptions($this->form_field->getOption('component'));

        return $this;
    }

    public function getButton(): FormFieldContract
    {
        if (is_null($this->form_field)) {
            throw new \RuntimeException(sprintf('Form button is not set yet to [%s] component', get_class($this)));
        }

        return $this->form_field;
    }

    public function getTranslationKey(string $key): string
    {
        return sprintf('button.%s', $key);
    }
}
