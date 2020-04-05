<?php

namespace Softworx\RocXolid\Forms\Contracts;

// rocXolid form builder contracts
use Softworx\RocXolid\Forms\Builders\Contracts\FormBuilder;
use Softworx\RocXolid\Forms\Builders\Contracts\FormFieldBuilder;
use Softworx\RocXolid\Forms\Builders\Contracts\FormFieldFactory;

// @todo: phpDoc
// @todo: add missing methods
interface Form
{
    /**
     * Form initializer.
     *
     * @return \Softworx\RocXolid\Forms\Contracts\Form
     */
    public function init(): Form;

    /**
     * Set the form builder.
     *
     * @param FormBuilder $form_builder
     * @return \Softworx\RocXolid\Forms\Contracts\Form
     */
    public function setFormBuilder(FormBuilder $form_builder): Form;

    /**
     * Get form builder.
     *
     * @return \Softworx\RocXolid\Forms\Builders\Contracts\FormBuilder
     */
    public function getFormBuilder(): FormBuilder;

    /**
     * Set the form field builder.
     *
     * @param \Softworx\RocXolid\Forms\Builders\Contracts\FormFieldBuilder
     * @return \Softworx\RocXolid\Forms\Contracts\Form
     */
    public function setFormFieldBuilder(FormFieldBuilder $form_field_builder): Form;

    /**
     * Get form field builder.
     *
     * @return \Softworx\RocXolid\Forms\Builders\Contracts\FormFieldBuilder
     */
    public function getFormFieldBuilder(): FormFieldBuilder;

    public function isFieldErrorsEnabled();

    public function enableFieldErrors();

    public function disableFieldErrors();

    public function isBrowserValidationEnabled();

    public function enableBrowserValidation();

    public function disableBrowserValidation();

    public function getInput(): array;
}
