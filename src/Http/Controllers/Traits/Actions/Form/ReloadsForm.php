<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions\Form;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;
// rocXolid form components
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;

/**
 * Trait to reload a form (upon field data change to adjust other fields' data).
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ReloadsForm
{
    /**
     * Reload Create/Update form to dynamically load related field values.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function formReload(CrudRequest $request, ?Crudable $model = null)//: Response
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));
        $model = $model ?? $repository->getModel();

        $this->setModel($model);

        // @todo: refactor to clearly identify the form we want to get, not artificially like this
        // put form->options['route-action'], or full identification data into the request
        // this can serve as a fallback
        if ($model->exists) {
            $form = $repository
                ->getForm($this->getFormParam($request, 'update'))
                    ->setFieldsRequestInput($request->input())
                    ->adjustUpdateBeforeSubmit($request);
        } else {
            $form = $repository
                ->getForm($this->getFormParam($request, 'create'))
                    ->setFieldsRequestInput($request->input())
                    ->adjustCreateBeforeSubmit($request);
        }

        $form_component = CrudFormComponent::build($this, $this)
                ->setForm($form)
                ->setRepository($repository);

        return $this->response
                ->replace($form_component->getDomId('fieldset'), $form_component->fetch('include.fieldset'))
                ->get();
    }
}
