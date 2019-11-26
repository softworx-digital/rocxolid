<?php

namespace Softworx\RocXolid\Forms\Fields;

use Illuminate\Support\Arr;
use Softworx\RocXolid\Contracts\Valueable;
use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Forms\Contracts\FormField;
use Softworx\RocXolid\Forms\Fields\AbstractFormField;

// @todo: doc properly
abstract class AbstractParentFormField extends AbstractFormField
{
    /**
     * @var array
     */
    protected $children = [];

    /**
     * Populate children array.
     *
     * @return mixed
     */
    abstract protected function createChildren();

    /**
     * Constructor.
     * 
     * @param string $name
     * @param string $type
     * @param \Softworx\RocXolid\Forms\Contracts\Form $parent
     * @param array $options
     * @return void
     */
    public function __construct(string $name, string $type, Form $parent, array $options = [])
    {
        parent::__construct($name, $type, $parent, $options);
        // If there is default value provided and  setValue was not triggered
        // in the parent call, make sure we generate child elements.
        if ($this->hasDefault) {
            $this->createChildren();
        }

        $this->checkIfFileType();
    }

    /**
     * Clone with children.
     * 
     * @return void
     */
    public function __clone()
    {
        foreach ((array)$this->children as $key => $child) {
            $this->children[$key] = clone $child;
        }
    }

    /**
     * Get child dynamically.
     *
     * @param string $name
     * @return \Softworx\RocXolid\Forms\Contracts\FormField
     */
    public function __get(string $name): FormField
    {
        return $this->getChild($name);
    }

    /**
     * @param mixed $val
     * @param int $index
     * @return \Softworx\RocXolid\Contracts\Valueable
     */
    public function setValue($value, int $index = 0): Valueable
    {
        parent::setValue($val);
        $this->createChildren();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render(array $options = [], bool $showLabel = true, bool $showField = true, bool $showError = true)
    {
        $options['children'] = $this->children;

        return parent::render($options, $showLabel, $showField, $showError);
    }

    /**
     * Get all children of the choice field.
     *
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * Get a child of the choice field.
     *
     * @return mixed
     */
    public function getChild($key)
    {
        return Arr::get($this->children, $key);
    }

    /**
     * Remove child.
     *
     * @return $this
     */
    public function removeChild($key): FormField
    {
        if ($this->getChild($key)) {
            unset($this->children[$key]);
        }

        return $this;
    }

    /**
     *{@inheritdoc}
     */
    public function isRendered(): bool
    {
        foreach ((array) $this->children as $key => $child) {
            if ($child->isRendered()) {
                return true;
            }
        }

        return parent::isRendered();
    }

    /**
     * Check if field has type property and if it's file add enctype/multipart to form.
     *
     * @return void
     */
    protected function checkIfFileType(): FormField
    {
        if ($this->getOption('type') === 'file') {
            $this->parent->setFormOption('files', true);
        }

        return $this;
    }

    /**
     *{@inheritdoc}
     */
    public function disable(): FormField
    {
        foreach ($this->children as $field) {
            $field->disable();
        }

        return $this;
    }

    /**
     *{@inheritdoc}
     */
    public function enable(): FormField
    {
        foreach ($this->children as $field) {
            $field->enable();
        }

        return $this;
    }

    /**
     *{@inheritdoc}
     */
    public function getValidationRules(): array
    {
        $rules = parent::getValidationRules();
        $childrenRules = $this->formHelper->mergeFieldsRules($this->children);

        return array_replace_recursive($rules, $childrenRules);
    }
}
