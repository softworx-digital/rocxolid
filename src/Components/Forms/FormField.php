<?php

namespace Softworx\RocXolid\Components\Forms;

use Softworx\RocXolid\Forms\Contracts\FormField as FormFieldContract;
use Softworx\RocXolid\Components\AbstractOptionableComponent;
use Softworx\RocXolid\Components\Contracts\Formable;
use Softworx\RocXolid\Components\Contracts\FormFieldable as ComponentFormFieldable;

class FormField extends AbstractOptionableComponent implements ComponentFormFieldable
{
    const ARRAY_TEMPLATE_NAME = 'array';

    protected $form_field;

    public static function buildInForm(Formable $form, FormFieldContract $form_field)
    {
        return static::build()
            ->setTranslationPackage($form->getTranslationPackage())
            ->setTranslationParam($form->getTranslationParam())
            ->setFormField($form_field);
    }

    public function setFormField(FormFieldContract $form_field): ComponentFormFieldable
    {
        $this->form_field = $form_field;

        $this->setOptions($this->form_field->getOption('component'));

        if ($placeholder = $this->getOption('attributes.placeholder', false)) {
            if (!is_numeric($placeholder)) {
                $this->mergeOptions([
                    'attributes' => [
                        'placeholder' => $this->translate($placeholder)
                    ]
                ]);
            }
        }

        return $this;
    }

    public function getFormField(): FormFieldContract
    {
        if (is_null($this->form_field)) {
            throw new \RuntimeException(sprintf('Form field is not set yet to [%s] component', get_class($this)));
        }

        return $this->form_field;
    }

    public function getDefaultTemplateName(): string
    {
        return $this->getFormField()->isArray()
             ? static::ARRAY_TEMPLATE_NAME
             : parent::getDefaultTemplateName();
    }

    public function getTranslationKey(string $key): string
    {
        return sprintf('field.%s', $key);
    }
}
