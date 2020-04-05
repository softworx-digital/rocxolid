<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Actions\Form;

// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid form components
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;
use Softworx\RocXolid\Components\Forms\FormField as FormFieldComponent;

/**
 * Trait to validate a form field.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait ValidatesFormField
{
    /**
     * Validate single Create/Update form field.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param string $field
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     */
    public function formValidateField(CrudRequest $request, string $field, ?Crudable $model = null)//: Response
    {
        dd(__METHOD__, 'INCOMPLETE');
    }
}
