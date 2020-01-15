<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions;

use App;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid form components
use Softworx\RocXolid\Components\Forms\FormField;
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;
// rocXolid forms
use Softworx\RocXolid\Forms\FileUploadForm;
// rocXolid common repositories
use Softworx\RocXolid\Common\Repositories\File\Repository as FileRepository;

/**
 * Trait to upload and assign a file to a resource.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait UploadsFile
{
    /**
     * Retrieve a form component to upload a file.
     *
     * @return \Softworx\RocXolid\Components\Forms\CrudForm
     */
    public function getFileUploadFormComponent()
    {
        if (!$this->hasModel()) {
            throw new \RuntimeException(sprintf('Controller [%s] is expected to have a model assigned', get_class($this)));
        }

        $repository = $this->getRepository();

        $form = $repository->createForm(FileUploadForm::class);

        return CrudFormComponent::build($this, $this)
            ->setForm($form)
            ->setRepository($repository);
    }

    /**
     * Upload the file and assign it to specified resource.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param int $id
     */
    public function fileUpload(CrudRequest $request, $id)
    {
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $this->setModel($repository->findOrFail($id));

        $form = $repository->createForm(FileUploadForm::class);

        $model_viewer_component = $this
            ->getModelViewerComponent($this->getModel());

        $file_repository = App::make(FileRepository::class);

        foreach ($request->file() as $data) {
            foreach ($data as $field_name => $data_files) {
                foreach ($data_files as $data_file) {
                    $file = $file_repository->handleUpload($data_file, $this->getModel(), $field_name);
                }

                $form_field_component = (new FormField())->setFormField($form->getFormField($field_name));

                $this->response->replace($form_field_component->getDomId('files', $field_name), $form_field_component->fetch('include.files'));
            }
        }

        return $this->response->get();
    }
}
