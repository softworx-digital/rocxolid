<?php

namespace Softworx\RocXolid\Forms\Builders;

// rocXolid requests
use Softworx\RocXolid\Http\Requests\FormRequest;
// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Forms\Contracts\Formable;
// rocXolid form builder contracts
use Softworx\RocXolid\Forms\Builders\Contracts\FormBuilder as FormBuilderContract;
use Softworx\RocXolid\Forms\Builders\Contracts\FormFieldBuilder;
use Softworx\RocXolid\Forms\Builders\Contracts\FormFieldFactory;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;

/**
 * Form builder and dependencies connector.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class FormBuilder implements FormBuilderContract
{
    /**
     * Reference to form field builder.
     *
     * @var \Softworx\RocXolid\Forms\Builders\Contracts\FormFieldBuilder
     */
    protected $form_field_builder;

    /**
     * Reference to form field factory.
     *
     * @var \Softworx\RocXolid\Forms\Builders\Contracts\FormFieldFactory
     */
    protected $form_field_factory;

    /**
     * Constructor.
     *
     * @param \Softworx\RocXolid\Forms\Builders\Contracts\FormFieldBuilder $form_field_builder
     * @param \Softworx\RocXolid\Forms\Builders\Contracts\FormFieldFactory $form_field_factory
     */
    public function __construct(
        FormFieldBuilder $form_field_builder,
        FormFieldFactory $form_field_factory)
    {
        $this->form_field_builder = $form_field_builder;
        $this->form_field_factory = $form_field_factory;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(Formable $container, CrudableModel $model, string $type, string $param, array $custom_options = [], array $data = []): Form
    {
        $form = app($this->validateFormType($type), [
            'param' => $param,
        ]);

        $this
            ->setFormDependencies($form, $container, $model)
            ->setFormOptions($form, $custom_options)
            ->setFormData($form, $data);

        // @todo: delegate to subbuilders, not form itself
        $form
            ->buildFields()
            ->init();

        return $form;
    }

    /**
     * Validates given class name to be suitable for form creation.
     *
     * @param string $form_class
     * @param string $parent_class
     * @return string
     */
    protected function validateFormType(string $type, string $interface = Form::class)
    {
        if (!class_exists($type)) {
            throw new \InvalidArgumentException(sprintf('Form class [%s] does not exist.', $type));
        }

        if (!(new \ReflectionClass($type))->implementsInterface($interface)) {
            throw new \InvalidArgumentException(sprintf('Class must be or extend [%s]; [%s] is not.', $interface, $type));
        }

        return $type;
    }

    /**
     * Set depedencies on existing form instance.
     *
     * @param \Softworx\RocXolid\Forms\Contracts\Form $form
     * @param \Softworx\RocXolid\Forms\Contracts\Formable $container
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @return \Softworx\RocXolid\Forms\Builders\Contracts\FormBuilder
     */
    protected function setFormDependencies(Form &$form, Formable $container, CrudableModel $model): FormBuilderContract
    {
        $form
            ->setFormBuilder($this)
            ->setFormFieldBuilder($this->form_field_builder)
            ->setFormFieldFactory($this->form_field_factory)
            ->setController($container)
            ->setModel($model)
            ->setRequest(app(FormRequest::class))
            ->setValidatorFactory(app('validator'));

        return $this;
    }

    /**
     * Set options on existing form instance.
     *
     * @param \Softworx\RocXolid\Forms\Contracts\Form $form
     * @param array $custom_options
     * @return \Softworx\RocXolid\Forms\Builders\Contracts\FormBuilder
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
     * @return \Softworx\RocXolid\Forms\Builders\Contracts\FormBuilder
     */
    protected function setFormData(Form &$form, array $data = []): FormBuilderContract
    {
        $form
            ->setData($data);

        return $this;
    }
}
