<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

use App;
use Softworx\RocXolid\Components\Contracts\Formable as FormableComponent;
use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Forms\Contracts\Formable as FormableContract;
use Softworx\RocXolid\Forms\Support\FormBuilder;

// @todo - asi do FormService pichnut
// @todo - check usage and reason
trait Formable
{
    protected $forms;

    protected $form_components = [];

    //protected $form_bulider; // is preferenced if set

    public function createForm($class, $param = FormableContract::FORM_PARAM): Form
    {
        $form = $this->getFormBuilder()->makeForm($class, $this, [], [], false);
        $form
            ->setParam($param)
            ->buildFields();

        return $form;
    }

    public function getForm($param = FormableContract::FORM_PARAM): Form
    {
        if (!$this->hasFormAssigned($param)) {
            $class = $this->getFormClass($param);

            if (!class_exists($class)) {
                throw new \InvalidArgumentException(sprintf('Form class [%s] does not exist.', $class));
            }

            $this->setForm($this->createForm($class, $param), $param);
        }

        return $this->forms[$param];
    }

    public function getForms(): array
    {
        return $this->forms;
    }

    public function setForm(Form $form, $param = FormableContract::FORM_PARAM): FormableContract
    {
        if (isset($this->forms[$param])) {
            throw new \InvalidArgumentException(sprintf('Form with given parameter [%s] is already set to [%s]', $param, get_class($this)));
        }

        $this->forms[$param] = $form;

        return $this;
    }

    public function getFormComponent($param = FormableContract::FORM_PARAM): FormableComponent
    {
        if (!isset($this->form_components[$param])) {
            $form = $this->getForm($param);

            $this->form_components[$param] = (new FormComponent())
                ->setForm($this->getForm($param));
        }

        return $this->form_components[$param];
    }

    public function setFormComponent(FormableComponent $form_component, $param = FormableContract::FORM_PARAM): FormableContract
    {
        $this->form_components[$param] = $form_component;

        return $this;
    }

    public function hasFormAssigned($param = FormableContract::FORM_PARAM): bool
    {
        return isset($this->forms[$param]);
    }

    public function hasFormClass($param = FormableContract::FORM_PARAM): bool
    {
        return class_exists($this->getFormClass($param));
    }

    public function getFormClass($param = FormableContract::FORM_PARAM): string
    {
        if (isset(static::$form_class) && isset(static::$form_class[$param])) {
            return static::$form_class[$param];
        } else {
            // @todo: Str case helper
            $form_class = str_replace('-', '', ucwords($param, '-')); // dash-separated to DashSeparated
            $reflection = new \ReflectionClass($this->getFormElementClass());

            $class = sprintf('%s\Forms\%s\%s', $reflection->getNamespaceName(), $reflection->getShortName(), $form_class);

            return $class;
        }
    }

    protected function getFormBuilder(): FormBuilder
    {
        if (!property_exists($this, 'form_builder') || is_null($this->form_builder)) {
            $with = [];

            foreach (['form_field_builder', 'form_field_factory', 'event_dispatcher'] as $component) {
                if (property_exists($this, $component)) {
                    $with[$component] = is_string($this->$component) ? App::make($this->$component) : $this->$component;
                }
            }

            $form_builder = App::make(FormBuilder::class, $with);

            if (property_exists($this, 'form_builder')) {
                $this->form_builder = $form_builder;
            }
        } elseif (property_exists($this, 'form_builder')) {
            $form_builder = $this->form_builder;
        }

        return $form_builder;
    }

    protected function getFormElementClass()
    {
        return $this;
    }
}
