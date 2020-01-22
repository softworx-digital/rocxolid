<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Crud;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid form components
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;

/**
 * Trait to create a resource.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait CreatesModels
{
    /**
     * Display the specified resource create form.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="create")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     */
    public function create(CrudRequest $request)//: View
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->getModel());

        $form = $repository->getForm($this->getFormParam($request));
        $form
            ->adjustCreate($request);

        $form_component = CrudFormComponent::build($this, $this)
            ->setForm($form)
            ->setRepository($repository);

        $model_viewer_component = $this
            ->getModelViewerComponent($this->getModel())
            ->setFormComponent($form_component)
            ->adjustCreate($request, $this);

        if ($request->ajax()) {
            return $this->response
                ->modal($model_viewer_component->fetch('modal.create'))
                ->get();
        } else {
            return $this
                ->getDashboard()
                ->setModelViewerComponent($model_viewer_component)
                ->render('model', [
                    'model_viewer_template' => 'create'
                ]);
        }
    }

    /**
     * Store the created model.
     *
     * @Softworx\RocXolid\Annotations\AuthorizedAction(policy_ability_group="write",policy_ability="create")
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest
     */
    public function store(CrudRequest $request)//: Response
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->getModel());

        $form = $repository->getForm($this->getFormParam($request));
        $form
            //->adjustCreate($request)
            ->adjustCreateBeforeSubmit($request)
            ->submit();

        if ($form->isValid()) {
            return $this->success($request, $repository, $form, 'create');
        } else {
            return $this->errorResponse($request, $repository, $form, 'create');
        }
    }
}
