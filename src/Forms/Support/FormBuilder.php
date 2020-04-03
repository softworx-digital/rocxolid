<?php

namespace Softworx\RocXolid\Forms\Support;

// rocXolid requests
use Softworx\RocXolid\Http\Requests\FormRequest;
// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Forms\Contracts\Formable;
use Softworx\RocXolid\Forms\Contracts\FormBuilder as FormBuilderContract;

class FormBuilder implements FormBuilderContract
{
    /**
     * @var FormFieldBuilder
     */
    protected $form_field_builder;
    /**
     * @var FormFieldFactory
     */
    protected $form_field_factory;

    /**
     * Constructor.
     *
     * @param FormFieldBuilder $form_field_builder
     * @param FormFieldFactory $form_field_factory
     */
    public function __construct(FormFieldBuilder $form_field_builder, FormFieldFactory $form_field_factory)
    {
        $this->form_field_builder = $form_field_builder;
        $this->form_field_factory = $form_field_factory;
    }

    /**
     * Get instance of the form which can be modified.
     *
     * @param string $form_class
     * @param array $custom_options
     * @param array $data
     * @param bool $build_form_fields
     * @return \Softworx\RocXolid\Forms\Contracts\Form
     */
    public function makeForm($form_class, Formable $form_holder, array $custom_options = [], array $data = [], $build_form_fields = true): Form
    {
        $form = $this->fetchForm($form_class);
        $form = $this->buildForm($form, $form_holder, $custom_options, $data, $build_form_fields);

        return $form;
    }

    /**
     * ...
     *
     * @param string $form_class
     * @return \Softworx\RocXolid\Forms\Contracts\Form
     */
    public function fetchForm($form_class): Form
    {
        $form = app($this->validateFormClass($form_class));

        return $form;
    }

    public function buildForm(Form $form, Formable $form_holder, array $custom_options = [], array $data = [], $build_form_fields = true): Form
    {
        $this
            ->setFormDependencies($form, $form_holder)
            ->setFormOptions($form, $custom_options)
            ->setFormData($form, $data);

        if ($build_form_fields) {
            $form->buildFields();
        }

        return $form;
    }

    /**
     * Validates given class name to be suitable for form creation.
     *
     * @param string $form_class
     * @param string $parent_class
     * @return string
     */
    protected function validateFormClass($form_class, $parent_class = Form::class)
    {
        if (!class_exists($form_class)) {
            throw new \InvalidArgumentException(sprintf('Form class [%s] does not exist.', $form_class));
        }

        if (!is_a($form_class, $parent_class, true)) {
            throw new \InvalidArgumentException(sprintf('Class must be or extend [%s]; [%s] is not.', $parent_class, $form_class));
        }

        return $form_class;
    }

    /**
     * Set depedencies on existing form instance.
     *
     * @param \Softworx\RocXolid\Forms\Contracts\Form $form
     * @return FormBuilderContract
     */
    protected function setFormDependencies(Form &$form, Formable $form_holder): FormBuilderContract
    {
        $form
            ->setFormBuilder($this)
            ->setFormFieldBuilder($this->form_field_builder)
            ->setFormFieldFactory($this->form_field_factory)
            ->setEventDispatcher($this->event_dispatcher)
            ->setHolderProperties($form_holder)
            ->setRequest(app(FormRequest::class))
            ->setValidatorFactory(app('validator'));

        return $this;
    }

    /**
     * Set options on existing form instance.
     *
     * @param \Softworx\RocXolid\Forms\Contracts\Form $form
     * @param array $custom_options
     * @return FormBuilderContract
     */
    protected function setFormOptions(Form &$form, array $custom_options = []): FormBuilderContract
    {
        $form
            ->adjustFormOptions()
            ->processFormOptions()
            ->setCustomOptions($custom_options);

        return $this;
    }

    /**
     * Set data on existing form instance.
     *
     * @param \Softworx\RocXolid\Forms\Contracts\Form $form
     * @param array $data
     * @return FormBuilderContract
     */
    protected function setFormData(Form &$form, array $data = []): FormBuilderContract
    {
        $form
            ->setData($data);

        return $this;
    }
}
