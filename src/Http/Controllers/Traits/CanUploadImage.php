<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

use App;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
use Softworx\RocXolid\Http\Responses\Contracts\AjaxResponse;
// rocXolid components
use Softworx\RocXolid\Components\Forms\FormField;
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;
// rocXolid forms
use Softworx\RocXolid\Forms\ImageUploadForm;
use Softworx\RocXolid\Common\Repositories\Image\Repository as ImageRepository;

trait CanUploadImage
{
    public function getImageUploadFormComponent()
    {
        if (!$this->hasModel()) {
            throw new \RuntimeException(sprintf('Controller [%s] is expected to have a model assigned', get_class($this)));
        }

        $repository = $this->getRepository();

        $form = $repository->createForm(ImageUploadForm::class);

        return CrudFormComponent::build($this, $this)
            ->setForm($form)
            ->setRepository($repository);
    }


    public function imageUpload(CrudRequest $request, $id)
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

        $form = $repository->createForm(ImageUploadForm::class);

        $model_viewer_component = $this
            ->getModelViewerComponent($this->getModel());

        $image_repository = App::make(ImageRepository::class);

        foreach ($request->file() as $data) {
            foreach ($data as $field_name => $data_files) {
                foreach ($data_files as $data_file) {
                    $image = $image_repository->handleUpload($data_file, $this->getModel(), $field_name);
                }

                $form_field_component = (new FormField())->setFormField($form->getFormField($field_name));

                $this->response->replace($form_field_component->getDomId('images', $field_name), $form_field_component->fetch('include.images'));
            }
        }

        return $this->response->get();
    }
}