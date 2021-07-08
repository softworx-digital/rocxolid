<?php

namespace Softworx\RocXolid\Forms\Contracts;

// rocXolid contracts
use Softworx\RocXolid\Contracts\Paramable;
use Softworx\RocXolid\Contracts\Optionable;
use Softworx\RocXolid\Contracts\Requestable;
use Softworx\RocXolid\Contracts\Translatable; // @todo ?
use Softworx\RocXolid\Contracts\Validable;
// rocXolid http contracts
use Softworx\RocXolid\Http\Responses\Contracts\AjaxResponse;
// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\FormFieldable;
use Softworx\RocXolid\Forms\Contracts\Buttonable;
// rocXolid form builder contracts
use Softworx\RocXolid\Forms\Builders\Contracts\FormBuilder;
use Softworx\RocXolid\Forms\Builders\Contracts\FormFieldBuilder;

// @todo documentationn
// @todo revise & finish
interface Form extends Paramable, FormFieldable, Buttonable, Optionable, Requestable, Translatable, Validable
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

    public function provideDomIdParam(): string;

    public function addToResponse(AjaxResponse &$response): Form;
}
