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

    protected function getTranslationKey(string $key, bool $use_repository_param): string
    {
        if (!$use_repository_param) {
            return sprintf('general.button.%s', $key);
        } elseif ($this->getButton() && $this->getButton()->getForm() && $this->getButton()->getForm()->getRepository()) {
            return sprintf('%s.button.%s', $this->getButton()->getForm()->getRepository()->getTranslationParam(), $key);
        } else {//if ($this->getController() && $this->getController()->getRepository())
            return '---form-button--- (' . __METHOD__ . ')';
        }

        return $key;
    }
}
