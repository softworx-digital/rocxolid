<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Forms;

use Illuminate\Support\Collection;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\Formable;
use Softworx\RocXolid\Forms\Contracts\Form;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;
// rocXolid controller traits
use Softworx\RocXolid\Http\Controllers\Traits\ElementMappable;

/**
 * Trait to connect the controller with a data form.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait HasForms
{
    use ElementMappable;
    use Actions\ReloadsForm;
    use Actions\ReloadsFormGroup;
    use Actions\ValidatesFormGroup;
    use Actions\AutocompletesFormField;

    /**
     * Forms container.
     *
     * @var array
     */
    protected $forms = [];

    /**
     * {@inheritDoc}
     */
    public function setForm(Form $form, string $param = Formable::FORM_PARAM): Formable
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
     * @todo refactor, do not set form here
     */
    public function getForm(CrudRequest $request, ?Crudable $model = null, ?string $param = null): Form
    {
        $param = $param ?? $this->getMappingParam($request, 'form', Formable::FORM_PARAM);
        $model = $model ?? $this->getRepository()->getModel();

        if (!$this->hasFormAssigned($param)) {
            $form = $this->makeForm($request, $model, $param);

            $this->setForm($form, $param);
        }

        return $this->forms[$param];
    }

    /**
     * @todo temporary public, revise
     */
    public function makeForm(CrudRequest $request, ?Crudable $model = null, ?string $param = null, array $custom_options = [], array $data = []): Form
    {
        $param = $param ?? $this->getMappingParam($request, 'form', Formable::FORM_PARAM);
        $model = $model ?? $this->getRepository()->getModel();

        return $this->formService()->createForm($model, $param, null, $custom_options, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function hasFormAssigned(string $param = Formable::FORM_PARAM): bool
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
