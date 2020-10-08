<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\FormField;
// rocXolid form fields
use Softworx\RocXolid\Forms\Fields\AbstractFormField;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

class AlertViewer extends AbstractFormField
{
    protected $default_options = [
        'type-template' => 'alert-viewer',
        'alert-type' => 'info',
        'alert-message' => '',
        // field wrapper
        'wrapper' => false,
        // component helper classes
        'helper-classes' => [
            'error-class' => 'has-error',
            'success-class' => 'has-success',
        ],
        // field label
        'label' => false,
        // field HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
    ];

    /**
     * Set the alert type.
     * Currently supported types are: primary, secondary, success, danger, warning, info, light, dark
     *
     * @param string $type
     * @return \Softworx\RocXolid\Forms\Contracts\FormField
     */
    protected function setAlertType(string $type): FormField
    {
        return $this->setComponentOptions('alert-type', $type);
    }

    /**
     * Set the alert heading.
     *
     * @param string $heading
     * @return \Softworx\RocXolid\Forms\Contracts\FormField
     */
    protected function setAlertHeading(string $heading): FormField
    {
        return $this->setComponentOptions('alert-heading', $heading);
    }

    /**
     * Set the alert message.
     *
     * @param string $message
     * @return \Softworx\RocXolid\Forms\Contracts\FormField
     */
    protected function setAlertMessage(string $message): FormField
    {
        return $this->setComponentOptions('alert-message', $message);
    }
}
