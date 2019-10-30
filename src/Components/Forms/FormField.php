<?php

namespace Softworx\RocXolid\Components\Forms;

use Softworx\RocXolid\Components\AbstractOptionableComponent;
use Softworx\RocXolid\Components\Contracts\FormFieldable as ComponentFormFieldable;
use Softworx\RocXolid\Forms\Contracts\FormField as FormFieldContract;

class FormField extends AbstractOptionableComponent implements ComponentFormFieldable
{
    const ARRAY_TEMPLATE_NAME = 'array';

    protected $form_field;

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

    protected function getTranslationKey(string $key, bool $use_repository_param): string
    {
        if (!$use_repository_param) {
            return sprintf('field.%s', $key);
        } elseif ($this->getFormField() && $this->getFormField()->getForm()) {
            if (method_exists($this->getFormField()->getForm(), 'getRepository')) {
                return sprintf('%s.field.%s', $this->getFormField()->getForm()->getRepository()->getTranslationParam(), $key);
            } else {
                return sprintf('general.field.%s', $key);
            }
        } else {//if ($this->getController() && $this->getController()->getRepository())
            return '---form-field--- (' . __METHOD__ . ')';
        }

        return $key;
    }
}
