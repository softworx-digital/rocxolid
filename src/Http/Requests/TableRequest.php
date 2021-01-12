<?php

namespace Softworx\RocXolid\Http\Requests;

use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid table contracts
use Softworx\RocXolid\Tables\Filters\Contracts\Filter;

/**
 * Request to utilize table related operations.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class TableRequest extends CrudRequest
{
    /**
     * Obtain data for filtering from request.
     *
     * @return array
     */
    public function getFilteringInput(): array
    {
        return $this->input(Filter::DATA_PARAM);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            Filter::DATA_PARAM => 'sometimes|array'
        ];
    }
}
