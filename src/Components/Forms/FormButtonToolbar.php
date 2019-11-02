<?php

namespace Softworx\RocXolid\Components\Forms;

use Softworx\RocXolid\Forms\Contracts\FormField as FormFieldContract;
use Softworx\RocXolid\Components\AbstractOptionableComponent;
use Softworx\RocXolid\Components\Contracts\Formable;
use Softworx\RocXolid\Components\Contracts\ButtonToolbarable as ComponentButtonToolbarable;
use Softworx\RocXolid\Components\Contracts\ButtonGroupable as ComponentButtonGroupable;

class FormButtonToolbar extends AbstractOptionableComponent implements ComponentButtonToolbarable
{
    protected $form_field;

    protected $buttongroups = [];

    public static function buildInForm(Formable $form, FormFieldContract $button_toolbar)
    {
        return static::build()
            ->setTranslationPackage($form->getTranslationPackage())
            ->setTranslationParam($form->getTranslationParam())
            ->setButtonToolbar($button_toolbar);
    }

    public function setButtonToolbar(FormFieldContract $form_field): ComponentButtonToolbarable
    {
        $this->form_field = $form_field;

        $this->setOptions($this->form_field->getOption('component'));

        return $this;
    }

    public function getButtonToolbar(): FormFieldContract
    {
        if (is_null($this->form_field)) {
            throw new \RuntimeException(sprintf('Form field is not set yet to [%s] component', get_class($this)));
        }

        return $this->form_field;
    }

    public function setButtonGroup(ComponentButtonGroupable $buttongroup): ComponentButtonToolbarable
    {
        $this->buttongroups[$buttongroup->getButtonGroup()->getName()] = $buttongroup;

        return $this;
    }

    public function getButtonGroups(): array
    {
        return $this->buttongroups;
    }
}
