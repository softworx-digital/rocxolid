<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Contracts\FormField;
use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class FormFieldGroupWizard extends AbstractFormField
{
    const DEFAULT_NAME = '__default__';

    protected $default_options = [
        'template' => 'wizard-step',
        // field wrapper
        'wrapper' => [
            'class' => 'col-md-6 col-xs-12 col-md-offset-3 step-tab-panel',
        ],
        // field label
        'label' => false,
        // field HTML attributes
        'attributes' => [
            'class' => 'form-field-group'
        ],
    ];

    protected function setImplicitOptions(): FormField
    {
        return $this->setComponentOptions('validation-url', $this->getForm()->getController()->getRoute('formValidateGroup', $this->getName(), $this->getForm()->getModel()));
    }
}
