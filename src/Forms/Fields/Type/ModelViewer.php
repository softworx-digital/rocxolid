<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\FormField;
// rocXolid form fields
use Softworx\RocXolid\Forms\Fields\AbstractFormField;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

class ModelViewer extends AbstractFormField
{
    /**
     * @var \Softworx\RocXolid\Models\Contracts\Crudable $model Model reference.
     */
    protected $model;

    protected $default_options = [
        'type-template' => 'model-viewer',
        'model' => null,
        'model-template' => 'include.data',
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
     * Get the value of model.
     *
     * @return \Softworx\RocXolid\Models\Contracts\Crudable|null
     */
    public function getModel(): ?Crudable
    {
        return $this->model;
    }

    /**
     * Set the value of model.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable|null $model
     * @return \Softworx\RocXolid\Forms\Contracts\FormField
     */
    protected function setModel(?Crudable $model): FormField
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Set model template to view.
     *
     * @param string $template
     * @return \Softworx\RocXolid\Forms\Contracts\FormField
     */
    protected function setModelTemplate(string $template): FormField
    {
        return $this->setComponentOptions('model-template', $template);
    }

    /**
     * Set model template assignments.
     *
     * @param array $assignments
     * @return \Softworx\RocXolid\Forms\Contracts\FormField
     */
    protected function setAssignments(array $assignments): FormField
    {
        return $this->setComponentOptions('assignments', $assignments);
    }
}
