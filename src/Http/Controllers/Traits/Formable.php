<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

use Illuminate\Support\Collection;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\Formable as FormableContract;
use Softworx\RocXolid\Forms\Contracts\Form;
// rocXolid controller traits
use Softworx\RocXolid\Http\Controllers\Traits\ElementMappable;

/**
 * Trait to connect the controller with a data form.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Formable
{
    use ElementMappable;

    /**
     * Forms container.
     *
     * @var array
     */
    protected $forms = [];

    /**
     * {@inheritDoc}
     */
    public function setForm(Form $form, string $param = FormableContract::FORM_PARAM): FormableContract
    {
        if ($this->hasFormAssigned($param)) {
            throw new \InvalidArgumentException(sprintf('Form with given parameter [%s] is already set to [%s]', $param, get_class($this)));
        }

        $this->forms[$param] = $form;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getForms(): Collection
    {
        return collect($this->forms);
    }

    /**
     * {@inheritDoc}
     */
    public function getForm(CrudRequest $request): Form
    {
        $param = $this->getMappingParam($request, 'form', FormableContract::FORM_PARAM);

        if (!$this->hasFormAssigned($param)) {
            $model = $this->getRepository()->getModel();

            $this->setForm($this->formService()->createForm($model, $param), $param);
        }

        return $this->forms[$param];
    }

    /**
     * {@inheritDoc}
     */
    public function hasFormAssigned(string $param = FormableContract::FORM_PARAM): bool
    {
        return isset($this->forms[$param]);
    }

    /**
     * {@inheritDoc}
     */
    public function getFormMappingType(string $param): string
    {
        return $this->getMappingType('form', $param);
    }
}
