<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions;

use App;
// relations
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid form components
use Softworx\RocXolid\Components\Forms\FormField;
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;
// rocXolid forms
use Softworx\RocXolid\Forms\ImageUploadForm;
use Softworx\RocXolid\Forms\GalleryUploadForm;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;
// rocXolid common repositories
use Softworx\RocXolid\Common\Repositories\Image\Repository as ImageRepository;

/**
 * Trait to upload and assign an image to a resource.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait UploadsImage
{
    /**
     * Retrieve a form component to upload a single image.
     *
     * @return \Softworx\RocXolid\Components\Forms\CrudForm
     */
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

    /**
     * Retrieve a form component to upload multiple images.
     *
     * @return \Softworx\RocXolid\Components\Forms\CrudForm
     * @todo verify type hints
     */
    public function getGalleryUploadFormComponent()
    {
        if (!$this->hasModel()) {
            throw new \RuntimeException(sprintf('Controller [%s] is expected to have a model assigned', get_class($this)));
        }

        $repository = $this->getRepository();

        $form = $repository->createForm(GalleryUploadForm::class);

        return CrudFormComponent::build($this, $this)
            ->setForm($form)
            ->setRepository($repository);
    }

    /**
     * Upload the image and assign it to specified resource.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @return array
     */
    public function imageUpload(CrudRequest $request, Crudable $model): array
    {
        $this->setModel($model);

        // not needed probably
        /*
        $repository = $this->getRepository($this->getRepositoryParam($request));

        $form = $repository->createForm(ImageUploadForm::class);

        $model_viewer_component = $this
            ->getModelViewerComponent($this->getModel());
        */

        $image_repository = App::make(ImageRepository::class);

        foreach ($request->file() as $data) {
            foreach ($data as $field_name => $data_files) {
                foreach ($data_files as $data_file) {
                    $image = $image_repository->handleUpload($data_file, $this->getModel(), $field_name);
                    // @todo: kinda hacky
                    $model_attribute = $field_name;

                    $this->getModel()->onImageUpload($image, $this->response);
                }

                // $form_field_component = (new FormField())->setFormField($form->getFormField($field_name));

                // $this->response->replace($form_field_component->getDomId('images', $field_name), $form_field_component->fetch('include.images'));
            }
        }

        return $this->onImageUpload($request, $model, $model_attribute);
    }

    /**
     * On image upload handler.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param string $model_attribute
     * @return array
     */
    protected function onImageUpload(CrudRequest $request, Crudable $model, string $model_attribute): array
    {
        if ($this->getModel()->$model_attribute() instanceof MorphOne) {
            $parent_image_upload_component = $this->getImageUploadFormComponent();
        } elseif ($this->getModel()->$model_attribute() instanceof MorphMany) {
            $parent_image_upload_component = $this->getGalleryUploadFormComponent();
        }

        return $this->response
            ->replace($parent_image_upload_component->getOption('id'), $parent_image_upload_component->fetch('upload'))
            ->get();
    }
}
