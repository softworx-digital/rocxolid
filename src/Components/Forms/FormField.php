<?php

namespace Softworx\RocXolid\Components\Forms;

use Softworx\RocXolid\Forms\Contracts\FormField as FormFieldContract;
use Softworx\RocXolid\Components\AbstractOptionableComponent;
use Softworx\RocXolid\Components\Contracts\FormFieldable as ComponentFormFieldable;

class FormField extends AbstractOptionableComponent implements ComponentFormFieldable
{
    const ARRAY_TEMPLATE_NAME = 'array';

    protected $form_field;

    public function setFormField(FormFieldContract $form_field): ComponentFormFieldable
    {
        $this->form_field = $form_field;

        $this->setOptions($this->form_field->getOption('component'));

        // @todo kinda "hotfixed", you can do better
        if ($view_package = $this->getOption('view-package', false)) {
            $this->setViewPackage($view_package);
        }

        // @todo kinda "hotfixed", you can do better
        if ($placeholder = $this->getOption('attributes.placeholder', false)) {
            if (!is_numeric($placeholder)) {
                $this->mergeOptions([
                    'attributes' => [
                        'placeholder' => $this->translate(sprintf('placeholder.%s', $placeholder), [], true)
                    ]
                ]);
            }
        }

        // @todo kinda "hotfixed", you can do better
        if ($title = $this->getOption('attributes.title', false)) {
            if (!is_numeric($title)) {
                $this->mergeOptions([
                    'attributes' => [
                        'title' => $this->translate(sprintf('placeholder.%s', $title), [], true)
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

    public function isHidden()
    {
        return $this->isOptionValue('attributes.data-dependency-controller-initial', 'disabled');
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
