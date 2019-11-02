<?php

namespace Softworx\RocXolid\Components\Forms;

use Softworx\RocXolid\Forms\Contracts\FormField as FormFieldContract;
use Softworx\RocXolid\Forms\Fields\Type\ButtonGroup;
use Softworx\RocXolid\Components\AbstractOptionableComponent;
use Softworx\RocXolid\Components\Contracts\Formable;
use Softworx\RocXolid\Components\Contracts\FormButtonable as ComponentFormButtonable;
use Softworx\RocXolid\Components\Contracts\ButtonGroupable as ComponentButtonGroupable;

/**
 * Enables object to be grouped into button group and to get buttons assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class FormButtonGroup extends AbstractOptionableComponent implements ComponentButtonGroupable
{
    /**
     * @var \Softworx\RocXolid\Forms\Fields\Type\ButtonGroup $form_button_group Assigned button group reference.
     */
    protected $form_button_group;

    /**
     * @var array $buttons Assigned buttons container.
     */
    protected $buttons = [];

    public static function buildInForm(Formable $form, FormFieldContract $form_button_group)
    {
        return static::build()
            ->setTranslationPackage($form->getTranslationPackage())
            ->setTranslationParam($form->getTranslationParam())
            ->setButtonGroup($form_button_group);
    }

    /**
     * {@inheritdoc}
     */
    public function setButtonGroup(ButtonGroup $form_button_group): ComponentButtonGroupable
    {
        $this->form_button_group = $form_button_group;

        $this->setOptions($this->form_button_group->getOption('component'));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonGroup(): ButtonGroup
    {
        if (is_null($this->form_button_group)) {
            throw new \RuntimeException(sprintf('Form field is not set yet to [%s] component', get_class($this)));
        }

        return $this->form_button_group;
    }

    /**
     * {@inheritdoc}
     */
    public function addButton(ComponentFormButtonable $button): ComponentButtonGroupable
    {
        $this->buttons[$button->getButton()->getName()] = $button;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }
}
