<?php

namespace Softworx\RocXolid\Components\Forms;

use Softworx\RocXolid\Forms\Contracts\FormField as FormFieldContract;
use Softworx\RocXolid\Components\General\Button;
use Softworx\RocXolid\Components\Contracts\FormButtonable as ComponentFormButtonable;

class FormButton extends Button implements ComponentFormButtonable
{
    protected $button;

    public function setButton(FormFieldContract $button): ComponentFormButtonable
    {
        $this->button = $button;

        $this->setOptions($this->button->getOption('component'));

        return $this;
    }

    public function getButton(): FormFieldContract
    {
        if (is_null($this->button)) {
            throw new \RuntimeException(sprintf('Form button is not set yet to [%s] component', get_class($this)));
        }

        return $this->button;
    }

    public function getTranslationKey(string $key): string
    {
        return sprintf('button.%s', $key);
    }
}
