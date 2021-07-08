<?php

namespace Softworx\RocXolid\Forms;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
// rocxolid general traits
use Softworx\RocXolid\Traits\Paramable;
use Softworx\RocXolid\Traits\MethodOptionable;
use Softworx\RocXolid\Traits\Requestable;
use Softworx\RocXolid\Traits\Translatable;
use Softworx\RocXolid\Traits\Validable;
// rocxolid http contracts
use Softworx\RocXolid\Http\Responses\Contracts\AjaxResponse;
// rocxolid form contracts
use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Forms\Contracts\FormField;
use Softworx\RocXolid\Forms\Builders\Contracts\FormBuilder;
use Softworx\RocXolid\Forms\Builders\Contracts\FormFieldBuilder;
use Softworx\RocXolid\Forms\Builders\Contracts\FormFieldFactory;

/**
 * @todo subject to refactoring
 */
abstract class AbstractForm implements Form
{
    use Paramable;
    use MethodOptionable;
    use Requestable;
    use Translatable;
    use Validable;
    use Traits\OptionsSetter;
    use Traits\FormFieldable;
    use Traits\Buttonable;

    /**
     * @var FormBuilder
     */
    private $form_builder = null;

    /**
     * @var FormFieldBuilder
     */
    private $form_field_builder = null;

    /**
     * @var FormFieldFactory
     */
    private $form_field_factory = null;

    /**
     * Flag to show validation errors.
     *
     * @var bool
     */
    private $show_field_errors = true;

    /**
     * Enable html5 validation.
     *
     * @var bool
     */
    private $browser_validation_enabled = true;

    /**
     * Form is beign rebuild flag.
     *
     * @var bool
     */
    private $is_rebuilding = false;

    /**
     * Flag whether the form has been already composed - form fields turned into form fields components.
     *
     * @var bool
     */
    private $is_composed = false;

    /**
     * Flag whether the form has been submitted.
     *
     * @var bool
     */
    private $is_submitted = false;

    /**
     * Field groups definition.
     *
     * @var bool|array
     */
    protected $fieldgroups = false;

    /**
     * Fields definition.
     *
     * @var bool|array
     */
    protected $fields = false;

    /**
     * Button toolbars definition.
     *
     * @var bool|array
     */
    protected $buttontoolbars = false;

    /**
     * Button groups definition.
     *
     * @var bool|array
     */
    protected $buttongroups = false;

    /**
     * Buttons definition.
     *
     * @var bool|array
     */
    protected $buttons = false;

    /**
     * protected $fields_order = [
     *     'field-1',
     *     ...
     * ];
     */
    protected $fields_order = null;

    /**
     * protected $buttons_order = [
     *     'button-1',
     *     ...
     * ];
     */
    protected $buttons_order = null;

    /**
     * Constructor
     *
     * @param string $param Table parameter serves as reference to better identify the table eg. in session key creation.
     */
    public function __construct(string $param)
    {
        $this->setParam($param);
    }

    /**
     * {@inheritDoc}
     *
     * @return \Softworx\RocXolid\Forms\Contracts\Form
     */
    public function init(): Form
    {
        return $this;
    }

    public function buildFields($validate = true): Form
    {
        $this
            ->getFormFieldBuilder()
                ->addDefinitionFields($this, [
                    'form_field_groups' => $this->getFieldGroupsDefinition(),
                    'form_fields' => $this->getFieldsDefinition()
                ], $this->fields_order)
                ->addDefinitionButtons($this, [
                    'button_toolbars' => $this->getButtonToolbarsDefinition(),
                    'button_groups' => $this->getButtonGroupsDefinition(),
                    'buttons' => $this->getButtonsDefinition(),
                ], $this->buttons_order);

        $this
            ->setFieldsRequestInput()
            ->setFieldsErrorsMessages();

        return $this;
    }

    /**
     * Rebuild the form fields from scratch.
     *
     * @return $this
     */
    public function rebuildFields(): Form
    {
        $this->is_rebuilding = true;
        $this->buildFields();
        $this->is_rebuilding = false;

        return $this;
    }

    public function submit(): Form
    {
        $this
            ->setSubmitted()
            ->validate()
            //->setRequestInput() // treba ??
            ->clearFormFieldsValues()
            ->setFieldsRequestInput()
            ->setFieldsErrorsMessages()
            ->setFieldsAfterSubmit();

        return $this;
    }

    public function submitGroup($group): Form
    {
        $this
            ->setSubmitted()
            ->validateGroup($group)
            //->setRequestInput() // treba ??
            ->clearFormFieldsValues()
            ->setFieldsRequestInput()
            ->setFieldsErrorsMessages()
            ->setFieldsAfterSubmit();

        return $this;
    }

    /**
     * Set the form builder instance.
     *
     * @param FormBuilder $form_builder
     * @return $this
     */
    public function setFormBuilder(FormBuilder $form_builder): Form
    {
        $this->form_builder = $form_builder;

        return $this;
    }

    /**
     * Get the instance of the form builder.
     *
     * @return FormBuilder
     */
    public function getFormBuilder(): FormBuilder
    {
        return $this->form_builder;
    }

    /**
     * Set the form field builder instance.
     *
     * @param FormFieldBuilder $form_field_builder
     * @return $this
     */
    public function setFormFieldBuilder(FormFieldBuilder $form_field_builder): Form
    {
        $this->form_field_builder = $form_field_builder;

        return $this;
    }

    /**
     * Get the instance of the form field builder.
     *
     * @return FormFieldBuilder
     */
    public function getFormFieldBuilder(): FormFieldBuilder
    {
        return $this->form_field_builder;
    }

    /**
     * Set the form field factory instance.
     *
     * @param FormFieldFactory $form_field_factory
     * @return $this
     */
    public function setFormFieldFactory(FormFieldFactory $form_field_factory): Form
    {
        $this->form_field_factory = $form_field_factory;

        return $this;
    }

    /**
     * Get the instance of the form field factory.
     *
     * @return FormFieldFactory
     */
    public function getFormFieldFactory(): FormFieldFactory
    {
        return $this->form_field_factory;
    }

    /**
     * Returns whether form fields validation errors should be shown.
     *
     * @return bool
     */
    public function isFieldErrorsEnabled(): bool
    {
        return $this->show_field_errors;
    }

    /**
     * Enable showing field validation errors.
     *
     * @return $this
     */
    public function enableFieldErrors(): Form
    {
        $this->show_field_errors = true;

        return $this;
    }

    /**
     * Disable showing field validation errors.
     *
     * @return $this
     */
    public function disableFieldErrors(): Form
    {
        $this->show_field_errors = false;

        return $this;
    }

    /**
     * Is browser validation enabled?
     *
     * @return bool
     */
    public function isBrowserValidationEnabled(): bool
    {
        return $this->browser_validation_enabled;
    }

    /**
     * Enable/disable browser validation.
     *
     * @return $this
     */
    public function enableBrowserValidation(): Form
    {
        $this->browser_validation_enabled = true;

        return $this;
    }

    /**
     * Disable browser validation.
     *
     * @return Form
     */
    public function disableBrowserValidation(): Form
    {
        $this->browser_validation_enabled = false;

        return $this;
    }

    public function getSessionParam($param = 'default')
    {
        return sprintf('%s-%s', md5(get_class($this)), $param);
    }

    public function setComposed($is = true)
    {
        $this->is_composed = $is;

        return $this;
    }

    public function isComposed()
    {
        return $this->is_composed;
    }

    public function setSubmitted($is = true)
    {
        $this->is_submitted = $is;

        return $this;
    }

    public function isSubmitted()
    {
        return $this->is_submitted;
    }

    public function wasSubmitted()
    {
        return $this->getRequest()->session()->exists($this->getSessionParam('input'));
    }

    public function setRequestInput($input = null)
    {
        $input = $input ?: $this->getRequest()->input();

        //$this->getRequest()->session()->flashInput($this->removeFilesFromInput($input));
        $this->getRequest()->session()->flashInput($input); // treba ? - treba pri neajaxovych, je tam redirect - treba redirect?

        return $this;
    }

    public function setFieldsAfterSubmit()
    {
        return $this;
    }

    public function setData($data)
    {
        return $this;
    }

    public function getInput(): array
    {
        $input = $this->isSubmitted()
               ? $this->getRequest()->input()
               //: $this->getRequest()->old();
               : $this->getRequest()->session()->get($this->getSessionParam('input'), []);

        return $input;
    }

    // @todo "hotfixed"
    public function getInputFieldValue($field, $validate = false)
    {
        $param = sprintf('%s.%s', FormField::SINGLE_DATA_PARAM, $field);

        if ($validate && !$this->getRequest()->has($param)) {
            throw new \InvalidArgumentException(sprintf('Undefined [%s] param in request', $param));
        }

        $value = $this->getRequest()->input($param, null);

        if (!is_null($value)) {
            return $value;
        }

        $input = collect($this->getInput());

        if (!$input->has(FormField::SINGLE_DATA_PARAM)) {
            return null;
        } elseif (($data = collect($input->get(FormField::SINGLE_DATA_PARAM))) && !$data->has($field)) {
            return null;
        }

        return $data->get($field);
    }

    public function setFieldsRequestInput(array $input = null): Form
    {
        $input = collect($input ?: $this->getInput());

        if ($input->has(FormField::SINGLE_DATA_PARAM)) {
            collect($input->get(FormField::SINGLE_DATA_PARAM))
                ->each(function ($value, $name) {
                    // @todo hotfixed, extremely ugly
                    if ($this->hasFormField($name)
                        && !is_null($value)
                        && !$this->getFormField($name)->hasOption('value')
                        && !$this->getFormField($name)->hasOption('force-value')) {
                        $this->getFormField($name)
                            ->setValue($value)
                            ->updateParent();
                    }
                });
        }

        /*
        if ($input->has(FormField::ARRAY_DATA_PARAM)) {
            collect($input->get(FormField::ARRAY_DATA_PARAM))
                ->each(function ($groupdata, $name) {
                    collect($groupdata)
                        ->each(function ($value, $index) use ($name) {
                            if ($this->hasFormField($name)) {
                                $this->getFormField($name)
                                    ->setValue($value, $index)
                                    ->updateParent();
                            }
                        });
                });
        }
        */

        // @todo "hotfixed"
        // the pivot handling doesn't belong here since it's CRUDable part
        if ($input->has(FormField::ARRAY_DATA_PARAM)) {
            collect($input->get(FormField::ARRAY_DATA_PARAM))
                ->each(function ($groupdata, $name) {
                    if (($name !== 'pivot') && $this->hasFormField($name)) {
                        $this->getFormField($name)
                            ->setValues(collect());
                    }

                    collect($groupdata)
                        ->each(function ($value, $index) use ($name) {
                            // @todo "hotfixed" - temporary fix - if the given field is pivot, then the $index holds the pivot-for field name
                            // $name holds the value 'pivot'
                            if (is_numeric($index)) {
                                if ($this->hasFormField($name)) {
                                    $this->getFormField($name)
                                        ->setValue($value, $index)
                                        ->updateParent();
                                }
                            } else {
                                $pivot_for = $index;

                                // @todo this is error prone, since there can be two pivot fields for different relations with the same name
                                collect($value)
                                    ->each(function ($pivot_fields, $pivot_field_index) use ($pivot_for) {
                                        collect($pivot_fields)
                                            ->each(function ($pivot_field_value, $name) use ($pivot_field_index, $pivot_for) {
                                                if ($this->hasFormField($name)) {
                                                    $this->getFormField($name)
                                                        ->setValue($pivot_field_value, $pivot_field_index)
                                                        ->updateParent();
                                                }
                                            });
                                    });
                            }
                        });
                });
        }

        return $this;
    }

    protected function setFieldsErrorsMessages(): Form
    {
        collect($this->getErrors()->getMessages())->each(function (array $messages, string $key) {
            list($key, $name) = explode('.', $key, 2);

            switch ($key) {
                case FormField::SINGLE_DATA_PARAM:
                    $this->getFormField($name)
                            ->setErrorMessages($messages)
                            ->updateComponent();
                    break;
                case FormField::ARRAY_DATA_PARAM:
                    // @todo ugly
                    $errors = [];

                    Arr::set($errors, $name, $messages);

                    collect($errors)->each(function ($groupmessages, $name) {
                        collect($groupmessages)->each(function ($messages, $index) use ($name) {
                            // @todo "hotfixed" - temporary fix - if the given field is pivot, then the $index holds the pivot-for field name
                            // $name holds the value 'pivot'
                            if (is_numeric($index)) {
                                $this->getFormField($name)
                                    ->setErrorMessages($messages, $index)
                                    ->updateComponent($index);
                            } else {
                                $pivot_for = $index;

                                // @todo this is error prone, since there can be two pivot fields for different relations with the same name
                                collect($messages)
                                    ->each(function ($pivot_fields, $pivot_field_index) use ($pivot_for) {
                                        collect($pivot_fields)
                                            ->each(function ($pivot_field_messages, $name) use ($pivot_field_index, $pivot_for) {
                                                $this->getFormField($name)
                                                    ->setErrorMessages($pivot_field_messages, $pivot_field_index)
                                                    ->updateComponent($pivot_field_index);
                                            });
                                    });
                            }
                        });
                    });
                    break;
            }
        });

        return $this;
    }

    public function getFieldGroups(): Collection
    {
        return collect($this->getFieldGroupsDefinition())->keys();
    }

    // @todo tieto zrejme upratat do nejakej support definition classy
    // @todo hotfixed
    public function adjustRequestInput(array $input): array
    {
        return $input;
    }

    protected function getFieldGroupsDefinition()
    {
        return $this->adjustFieldGroupsDefinition($this->fieldgroups);
    }

    protected function adjustFieldGroupsDefinition($fieldgroups)
    {
        return $fieldgroups;
    }

    protected function getFieldsDefinition()
    {
        return $this->adjustFieldsDefinition($this->fields);
    }

    protected function adjustFieldsDefinition($fields)
    {
        return $fields;
    }

    protected function getButtonToolbarsDefinition()
    {
        return $this->adjustButtonToolbarsDefinition($this->buttontoolbars);
    }

    protected function adjustButtonToolbarsDefinition($buttontoolbars)
    {
        return $buttontoolbars;
    }

    protected function getButtonGroupsDefinition()
    {
        return $this->adjustButtonGroupsDefinition($this->buttongroups);
    }

    protected function adjustButtonGroupsDefinition($buttongroups)
    {
        return $buttongroups;
    }

    protected function getButtonsDefinition()
    {
        return $this->adjustButtonsDefinition($this->buttons);
    }

    protected function adjustButtonsDefinition($buttons)
    {
        return $buttons;
    }

    public function provideDomIdParam(): string
    {
        return md5(get_class($this));
    }

    public function addToResponse(AjaxResponse &$response): Form
    {
        return $this;
    }
}
