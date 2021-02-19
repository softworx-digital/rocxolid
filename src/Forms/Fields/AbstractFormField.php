<?php

namespace Softworx\RocXolid\Forms\Fields;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Valueable;
use Softworx\RocXolid\Contracts\PivotValueable;
use Softworx\RocXolid\Contracts\ErrorMessageable;
use Softworx\RocXolid\Contracts\Optionable;
use Softworx\RocXolid\Contracts\Translatable;
// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Forms\Contracts\FormField;
use Softworx\RocXolid\Forms\Contracts\FormFieldable;
// rocXolid traits
use Softworx\RocXolid\Traits\Valueable as ValueableTrait;
use Softworx\RocXolid\Traits\PivotValueable as PivotValueableTrait;
use Softworx\RocXolid\Traits\ErrorMessageable as ErrorMessageableTrait;
use Softworx\RocXolid\Traits\MethodOptionable as MethodOptionableTrait;
use Softworx\RocXolid\Traits\Translatable as TranslatableTrait;
// rocXolid form field traits
use Softworx\RocXolid\Forms\Fields\Traits\ComponentOptionsSetter as ComponentOptionsSetterTrait;

// @todo refactor
abstract class AbstractFormField implements FormField, Valueable, PivotValueable, Optionable, ErrorMessageable, Translatable
{
    use ValueableTrait;
    use PivotValueableTrait;
    use MethodOptionableTrait;
    use ErrorMessageableTrait;
    use ComponentOptionsSetterTrait;
    use TranslatableTrait; // @todo needed?

    /**
     * Name of the field.
     *
     * @var string
     */
    protected $name;
    /**
     * Type of the field.
     *
     * @var string
     */
    protected $type;
    /**
     * @var Form
     */
    protected $form;
    /**
     * @var FormFieldable
     */
    protected $parent;
    /**
     * @var bool
     */
    protected $is_hidden = false;
    /**
     * 'template' => 'rocXolid::form.field.text',
     * 'type-template' => 'text',
     * 'attributes' => [
     *     'class' => 'form-control'
     * ],
     * 'wrapper' => [
     *     'attributes' => [
     *         'class' => 'form-group'
     *     ]
     * ],
     * 'label' => [
     *     'title' => 'name',
     *     'attributes' => [
     *         'class' => 'control-label col-md-2 col-sm-2 col-xs-12',
     *         'for' => 'name'
     *     ],
     * ],
     * 'validation' => [
     *     'rules' => [
     *         'required',
     *         'max:255',
     *         'min:2',
     *         'active_url',
     *     ],
     *     'error' => [
     *         'attributes' => [
     *             'class' => 'has-error'
     *         ]
     *     ]
     * ],
     */
    protected $default_options = [];

    /**
     * @param string $name
     * @param string $type
     * @param Form $form
     * @param FormFieldable $parent
     * @param array $options
     */
    public function __construct($name, $type, Form $form, FormFieldable $parent, array $options = [])
    {
        $options = array_replace_recursive($this->default_options, $options);

        $this
            ->setName($name)
            ->setType($type)
            ->setForm($form)
            ->setParent($parent)
            ->setImplicitOptions()
            ->setOptions($options)
            ->init();
    }

    protected function init()
    {
        return $this;
    }

    /**
     * Set system name of the field.
     *
     * @param string $name
     * @return $this
     */
    protected function setName($name): FormField
    {
        $this->name = $name;

        return $this;
    }

    protected function setType($type): FormField
    {
        $this->type = $type;

        return $this;
    }

    protected function setForm($form): FormField
    {
        $this->form = $form;

        return $this;
    }

    protected function setParent($parent): FormField
    {
        $this->parent = $parent;

        return $this;
    }

    protected function setImplicitOptions(): FormField
    {
        return $this;
    }

    /**
     * Get system name of the field.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get system name of the field.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Check if the field component is hidden.
     *
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->is_hidden;
    }

    /**
     * Check if the field is required.
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        return in_array('required', $this->getOption('validation.rules', []));
    }

    /**
     * Check if the field has maximum length declared.
     *
     * @return bool
     */
    public function isMaxlength(): bool
    {
        return in_array('max', $this->getOption('validation.rules', []))
            || in_array('maxplain', $this->getOption('validation.rules', []));
    }

    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * @return FormFieldable
     */
    public function getParent(): FormFieldable
    {
        return $this->parent;
    }

    public function isArray()
    {
        return $this->getOption('component.array', false);
    }

    public function isPivot()
    {
        return filled($this->pivot_relation_name);
    }

    /**
     * {@inheritDoc}
     */
    // @todo kinda hacky, don't like this approach
    public function getTitle(): string
    {
        if ($this->hasOption('component.label.title-translated')) {
            return $this->getOption('component.label.title-translated');
        }

        return $this->getForm()->getController()->translate(sprintf('field.%s', $this->getOption('component.label.title', $this->getName())));
    }

    // @todo kinda hacky, don't like this approach
    public function updateParent()
    {
        // @todo zavolat parent update + spravne setnuty parent ?
        // to ale chce, aby aj field mal spravne setnutu referenciu na parenta, co zatial nema, zatial je to len form
        if ($group_name = $this->getOption('component.group', false)) {
            if ($this->form->getFormFieldGroup($group_name)->getOption('component.array', false)) {
                $this->form->getFormFieldGroup($group_name)->setGroupCount($this->getValues()->count());
            }
        }

        return $this;
    }

    // @todo "hotfixed", you can do better
    public function updateComponent(int $index = 0)
    {
        if ($this->hasErrorMessages($index)) {
            $key = $this->isArray()
                 ? sprintf('wrapper-%s', $index)
                 : 'wrapper';

            $this->setComponentOptions($key, [
                'attributes' => [
                    'class' => sprintf(
                        '%s %s',
                        $this->getOption('component.wrapper.attributes.class', false),
                        $this->getOption('component.helper-classes.error-class', 'has-error')
                    )
                ]
            ]);
        }

        return $this;
    }

    /**
     * Get HTML name of the field.
     *
     * @param int $index Field index.
     * @return string
     */
    public function getFieldName(int $index = 0): string
    {
        if ($this->isPivot()) {
            return $this->getPivotFieldName($index);
        }

        if ($this->isArray()) {
            return sprintf('%s[%s][%s]', self::ARRAY_DATA_PARAM, $this->name, $index);
        } else {
            return sprintf('%s[%s]', self::SINGLE_DATA_PARAM, $this->name);
        }
    }

    /**
     * Get HTML name of the pivot field.
     *
     * @param string $attribute Pivot attribute name.
     * @param int $index Field index.
     * @return string
     */
    public function getPivotFieldName(int $index = 0): string
    {
        if ($this->isArray()) {
            return sprintf('%s[pivot][%s][%s][%s]', self::ARRAY_DATA_PARAM, $this->pivot_relation_name, $index, $this->name);
        } else {
            return sprintf('%s[pivot][%s][%s]', self::SINGLE_DATA_PARAM, $this->pivot_relation_name, $this->name);
        }
    }

    /**
     * Get HTML value of the field.
     *
     * @return string
     */
    public function getFieldValue(int $index = 0)
    {
        if ($this->isArray()) {
            return $this->getIndexValue($index);
        } else {
            return $this->getValue();
        }
    }

    protected function adjustValueBeforeSet($value)
    {
        return $value;
    }

    // @todo refactoring & unit testing candidate
    public function isFieldValue($value, $index = 0): bool
    {
        return !is_null($this->getFieldValue($index)) && !is_null($value) && ((string)$this->getFieldValue($index) === (string)$value);
    }

    public function getFinalValue()
    {
        $model = $this->getForm()->getModel();

        // @todo temporary hotfix for BelongsToMany fields
        if ($this->isArray() && method_exists($model, $this->name) && ($model->{$this->name}() instanceof BelongsToMany)) {
            $values = $this->getValues();

            // @todo awkward
            $relation = $model->{$this->name}();
            $related = $relation->getRelated();
            $pivot_fields = $this->getForm()->getPivotFormFields($relation);

            $this->setPivotData(collect());

            $values->each(function ($related_key, $index) use ($model, $relation, $pivot_fields) {
                $pivot_data = collect();

                $pivot_fields->each(function ($pivot_field) use ($pivot_data, $index) {
                    $pivot_data->put($pivot_field->getName(), $pivot_field->getIndexValue($index));
                });

                // $pivot_data = collect($data[$relation->getPivotAccessor()] ?? [])->get($related_key);

                $this->addNewPivot($relation, [
                    $relation->getForeignPivotKeyName() => $model->getKey(),
                    $relation->getRelatedPivotKeyName() => $related_key,
                ] + $pivot_data->toArray());
            });

            return $this->getPivotData()->toArray();
        }

        if ($this->isArray()) {
            return $this->getValues();
        }

        return $this->getValue();
    }

    /**
     * Get validation rule key for the field.
     *
     * @return string
     */
    public function getRuleKey()
    {
        if ($this->isPivot()) {
            if ($this->isArray()) {
                return sprintf('%s.pivot.%s.*.%s', self::ARRAY_DATA_PARAM, $this->pivot_relation_name, $this->name);
            } else {
                return sprintf('%s.pivot.%s.%s', self::SINGLE_DATA_PARAM, $this->pivot_relation_name, $this->name);
            }
        }

        if ($this->isArray()) {
            return sprintf('%s.%s.*', self::ARRAY_DATA_PARAM, $this->name);
        } else {
            return sprintf('%s.%s', self::SINGLE_DATA_PARAM, $this->name);
        }
    }

    protected function setValidation($validation): FormField
    {
        $this->mergeOptions([
            'validation' => $validation
        ]);

        return $this;
    }

    // @todo hotfixed
    protected function setForceValue($value): FormField
    {
        $this->mergeOptions([
            'force-value' => $value
        ]);

        $this->setValue($value);

        return $this;
    }

    protected function makeRoute($route_name)
    {
        return route($route_name);
    }

    // @todo toto do separatnej parser classy / viac class, ktore to budu handlovat - pozriet ako sa riesia validation messages
    // Forms\Fields\Support\... - navrhnut strukturu
    // resp cele nejako inak - domysliet
    /*
    protected function processDomDataAttributeValues($attribute, $value)
    {
        switch ($attribute)
        {
            case 'duplicate-form-row':
                if ($value == ':form-first')
                {
                    return sprintf('#%s fieldset > :first-child', $this->form->getOption('component.id'));
                }
                else
                {
                    throw new \InvalidArgumentException(sprintf('Invalid value [%s] for DOM data attribute [%s]', $value, $attribute));
                }
            default:
                throw new \InvalidArgumentException(sprintf('Invalid attribute [%s] for DOM data', $attribute));
        }
    }
    */
}
