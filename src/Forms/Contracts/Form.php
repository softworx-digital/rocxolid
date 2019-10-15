<?php

namespace Softworx\RocXolid\Forms\Contracts;

// @TODO: phpDoc
interface Form
{
    public function setHolderProperties(Formable $holder): Form;

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
     * @return \Softworx\RocXolid\Forms\Contracts\FormBuilder
     */
    public function getFormBuilder(): FormBuilder;

    /**
     * Set the form field builder.
     *
     * @param \Softworx\RocXolid\Forms\Contracts\FormFieldBuilder
     * @return \Softworx\RocXolid\Forms\Contracts\Form
     */
    public function setFormFieldBuilder(FormFieldBuilder $form_field_builder): Form;

    /**
     * Get form field builder.
     *
     * @return \Softworx\RocXolid\Forms\Contracts\FormFieldBuilder
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
