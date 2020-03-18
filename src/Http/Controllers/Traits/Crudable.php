<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid forms
use Softworx\RocXolid\Forms\AbstractCrudForm as AbstractCrudForm;

/**
 * Trait to make the controller able to handle all the CRUD operations and give appropriate responses.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Crudable
{
    use Crud\ListsModels;
    use Crud\CreatesModels;
    use Crud\ReadsModels;
    use Crud\UpdatesModels;
    use Crud\DestroysModels;
    use Crud\Response\ProvidesSuccessResponse;
    use Crud\Response\ProvidesErrorResponse;
    use Crud\Response\ProvidesDestroyResponse;

    // protected static $model_class; // should be defined in specific class

    public static function getModelClass(): string
    {
        return static::$model_class;
    }

    // @todo: maybe some different approach
    public function isModelActionAvailable(CrudableModel $model, string $action): bool
    {
        return true;
    }

    // @todo: this doesn't belong here, or?
    public function makeForm(string $param, ?CrudableModel $model, ?string $form_class = null): AbstractCrudForm
    {
        $repository = $this->getRepository($param);
        $model = $model ?? $repository->getModel();

        $this->setModel($model);

        if (is_null($form_class)) {
            $form = $repository->getForm($param);
        } else {
            $form = $repository->createForm($form_class, $param);
        }

        return $form;
    }

    protected function getFormParam(CrudRequest $request, ?string $method = null): string
    {
        $method = $method ?? $request->route()->getActionMethod();

        if ($request->filled('_section')) {
            $method = sprintf('%s.%s', $method, $request->_section);

            if (isset($this->form_mapping[$method])) {
                return $this->form_mapping[$method];
            }
        }

        if (!isset($this->form_mapping[$method])) {
            throw new \InvalidArgumentException(sprintf('No controller [%s] form mapping for method [%s]', get_class($this), $method));
        }

        return $this->form_mapping[$method];
    }
}
