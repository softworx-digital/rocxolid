<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;

/**
 * Trait to make the controller able to handle all the CRUD operations and give appropriate response.
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
    // @todo response methods - inject model viewer, do not 'create' it (same for CRUD traits subusing Response traits)
    use Crud\Response\ProvidesSuccessResponse;
    use Crud\Response\ProvidesErrorResponse;

    // protected static $model_type; // should be defined in specific controller class

    /**
     * Initiate model instance to be used with controller.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @return \Softworx\RocXolid\Models\Contracts\Crudable
     */
    public function initModel(CrudableModel $model): CrudableModel
    {
        return $model;
    }

    /**
     * Provide the model type the controller works with.
     * Each controller is assigned only one model type.
     *
     * @return string
     */
    public static function getModelType(): string
    {
        return static::$model_type ?? self::guessModelType();
    }

    /**
     * Naively guess the model type based on controller's namespace.
     * Replace the 'Http\Controller' subnamespace with 'Model' in the controller's namespace name.
     *
     * @return string
     */
    private static function guessModelType(): string
    {
        $model_type = str_replace('Http\Controllers', 'Models', (new \ReflectionClass(static::class))->getNamespaceName());

        if (!class_exists($model_type)) {
            throw new \RuntimeException(sprintf('Controller [%s] guessed unexisting model type [%s] to work with.', static::class, $model_type));
        }

        if (!(new \ReflectionClass($model_type))->implementsInterface(CrudableModel::class)) {
            throw new \RuntimeException(sprintf('Controller [%s] guessed model type [%s] that is not [%s].', static::class, $model_type, CrudableModel::class));
        }

        return $model_type;
    }
}
