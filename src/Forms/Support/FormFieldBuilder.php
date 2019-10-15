<?php

namespace Softworx\RocXolid\Forms\Support;

// contracts
use Softworx\RocXolid\Contracts\EventDispatchable;
// form contracts
use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Forms\Contracts\FormField;
use Softworx\RocXolid\Forms\Contracts\FormFieldable;
use Softworx\RocXolid\Forms\Contracts\FormFieldBuilder as FormFieldBuilderContract;
// button & field groups
use Softworx\RocXolid\Forms\Fields\Type\ButtonGroup;
use Softworx\RocXolid\Forms\Fields\Type\ButtonToolbar;
use Softworx\RocXolid\Forms\Fields\Type\FormFieldGroup;
use Softworx\RocXolid\Forms\Fields\Type\FormFieldGroupAddable;

/**
 *
 */
class FormFieldBuilder implements FormFieldBuilderContract
{
    private static $required_field_settings = [
        'type',
        'options',
    ];

    public function addDefinitionFields(Form $form, $definition, $form_fields_order_definition): FormFieldBuilderContract
    {
        $form_field_groups = [];
        $form_fields = [];

        $this
            ->validateFormFieldsDefinition($definition)
            ->processFormFieldsDefinition($form, $form, $definition, $form_field_groups, $form_fields);

        $form
            ->setFormFieldGroups($form_field_groups)
            ->setFormFields($form_fields)
            ->reorderFormFields($form_fields_order_definition);

        return $this;
    }

    public function addDefinitionButtons(Form $form, $definition, $form_buttons_order_definition): FormFieldBuilderContract
    {
        $button_toolbars = [];
        $button_groups = [];
        $buttons = [];

        $this
            ->validateButtonsDefinition($definition)
            ->processButtonsDefinition($form, $form, $definition, $button_toolbars, $button_groups, $buttons);

        $form
            ->setButtontoolbars($button_toolbars)
            ->setButtongroups($button_groups)
            ->setButtons($buttons);

        return $this;
    }

    protected function processDefinition(Form $form, FormFieldable $parent, $definition, &$items, $name_prefix = null): FormFieldBuilderContract
    {
        foreach ($definition as $name => $settings) {
            $type = null;
            $options = null;

            $this
                ->processFieldSettings($name, $name_prefix, $settings, $type, $options)
                ->processFieldName($name, $name_prefix)
                ->processFieldType($type)
                ->processFieldOptions($form, $name, $type, $options);

            if (isset($form_fields[$name])) {
                throw new \InvalidArgumentException(sprintf('Field [%s] is already set in form fields', $name));
            }

            $items[$name] = $form->getFormFieldFactory()->makeField($form, $parent, $type, $name, $options);
        }

        return $this;
    }

    protected function validateFormFieldsDefinition($definition): FormFieldBuilderContract
    {
        if (!isset($definition['form_field_groups'])) {
            throw new \InvalidArgumentException(sprintf('Form field groups not defined in definition [%s]', print_r($definition, true)));
        } elseif (!is_bool($definition['form_field_groups']) && !is_array($definition['form_field_groups'])) {
            throw new \InvalidArgumentException(sprintf('Invalid form field groups definition [%s], boolean or array expected', gettype($definition['form_field_groups'])));
        }

        if (!isset($definition['form_fields'])) {
            throw new \InvalidArgumentException(sprintf('Form fields not defined in definition [%s]', print_r($definition, true)));
        }
        //elseif (!is_bool($definition['form_fields']) && !is_array($definition['form_fields']))
        elseif (!is_array($definition['form_fields'])) {
            throw new \InvalidArgumentException(sprintf('Invalid form fields definition [%s], array expected', gettype($definition['form_fields'])));
            //throw new \InvalidArgumentException(sprintf('Invalid form fields definition [%s], boolean or array expected', gettype($definition['form_fields'])));
        }

        return $this;
    }

    protected function processFormFieldsDefinition(Form $form, FormFieldable $parent, $definition, &$form_field_groups, &$form_fields, $name_prefix = null): FormFieldBuilderContract
    {
        if ($definition['form_field_groups'] === true) {
            $definition['form_field_groups'] = [
                FormFieldGroup::DEFAULT_NAME => [
                    'type' => FormFieldGroup::class,
                    'options' => []
                ]
            ];
        } elseif ($definition['form_field_groups'] === false) {
            $definition['form_field_groups'] = [];
        }

        $this->processDefinition($form, $parent, $definition['form_field_groups'], $form_field_groups, $name_prefix);
        $this->processDefinition($form, $parent, $definition['form_fields'], $form_fields, $name_prefix);

        return $this;
    }

    protected function validateButtonsDefinition($definition): FormFieldBuilderContract
    {
        if (!isset($definition['button_toolbars'])) {
            throw new \InvalidArgumentException(sprintf('Button toolbars not defined in definition [%s]', print_r($definition, true)));
        } elseif (!is_bool($definition['button_toolbars']) && !is_array($definition['button_toolbars'])) {
            throw new \InvalidArgumentException(sprintf('Invalid button toolbars definition [%s], boolean or array expected', gettype($definition['button_toolbars'])));
        }

        if (!isset($definition['button_groups'])) {
            throw new \InvalidArgumentException(sprintf('Button groups not defined in definition [%s]', print_r($definition, true)));
        } elseif (!is_bool($definition['button_groups']) && !is_array($definition['button_groups'])) {
            throw new \InvalidArgumentException(sprintf('Invalid button groups definition [%s], boolean or array expected', gettype($definition['button_groups'])));
        }

        if (!isset($definition['buttons'])) {
            throw new \InvalidArgumentException(sprintf('Buttons not defined in definition [%s]', print_r($definition, true)));
        } elseif (!is_bool($definition['buttons']) && !is_array($definition['buttons'])) {
            throw new \InvalidArgumentException(sprintf('Invalid buttons definition [%s], boolean or array expected', gettype($definition['buttons'])));
        }

        return $this;
    }

    protected function processButtonsDefinition(Form $form, FormFieldable $parent, $definition, &$button_toolbars, &$button_groups, &$buttons, $name_prefix = null): FormFieldBuilderContract
    {
        if ($definition['button_toolbars'] === true) {
            $definition['button_toolbars'] = [
                ButtonToolbar::DEFAULT_NAME => [
                    'type' => ButtonToolbar::class,
                    'options' => []
                ]
            ];
        } elseif ($definition['button_toolbars'] === false) {
            $definition['button_toolbars'] = [];
        }

        if ($definition['button_groups'] === true) {
            $definition['button_groups'] = [
                ButtonGroup::DEFAULT_NAME => [
                    'type' => ButtonGroup::class,
                    'options' => []
                ]
            ];
        } elseif ($definition['button_groups'] === false) {
            $definition['button_groups'] = [];
        }

        $this->processDefinition($form, $parent, $definition['button_toolbars'], $button_toolbars, $name_prefix);
        $this->processDefinition($form, $parent, $definition['button_groups'], $button_groups, $name_prefix);
        $this->processDefinition($form, $parent, $definition['buttons'], $buttons, $name_prefix);

        return $this;
    }

    protected function processFieldSettings($name, $name_prefix, &$settings, &$type, &$options): FormFieldBuilderContract
    {
        foreach (self::$required_field_settings as $required) {
            if (!isset($settings[$required])) {
                throw new \InvalidArgumentException(sprintf('Required field setting [%s] not found in settings for field [%s], prefix [%s]: %s', $required, $name, $name_prefix, print_r($settings, true)));
            }
        }

        extract($settings); // $type, $options

        return $this;
    }

    protected function processFieldName(&$name, $name_prefix): FormFieldBuilderContract
    {
        $name = is_null($name_prefix) ? $name : sprintf('%s-%s', $name_prefix, $name);

        if (!preg_match('/[\w-]+/', $name)) {
            throw new \InvalidArgumentException(sprintf('Invalid field name [%s] given', $name));
        }

        return $this;
    }

    protected function processFieldType(&$type): FormFieldBuilderContract
    {
        if (!class_exists($type)) {
            throw new \InvalidArgumentException(sprintf('Invalid field type [%s] given', $type));
        }

        return $this;
    }

    protected function processFieldOptions(Form $form, $name, $type, &$options): FormFieldBuilderContract
    {
        /*
        foreach ($options as $option => $value)
        {
            switch ($option)
            {
                case 'xxx':
                    $options['yyy'] = 'zzz';
                    break;
            }
        }
        */

        return $this;
    }
}
