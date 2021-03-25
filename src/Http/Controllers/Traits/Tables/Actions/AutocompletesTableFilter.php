<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Tables\Actions;

use Illuminate\Http\JsonResponse;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Searchable;

/**
 * Trait to enable autocompletion feature for table filter fields.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait AutocompletesTableFilter
{
    /**
     * Process the incoming table filter autocomplete request.
     * Retrieve the table and filter field upon request params, obtain results from filter's autocompletion.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param string $param Controller's table param.
     * @param string $filter Table's filter name.
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableFilterAutocomplete(CrudRequest $request, string $param, string $filter): JsonResponse
    {
        $table = $this->getTable($request, $param);
        $filter = $table->getFilter($filter);
        $data = $filter->autocomplete($request->input('q'));

        return response()->json($data->transform(function (Searchable $model) {
            return $model->toSearchResult();
        }));
    }
}
