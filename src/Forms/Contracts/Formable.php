<?php

namespace Softworx\RocXolid\Forms\Contracts;

use Illuminate\Support\Collection;
// rocXolid utils
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\Form;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Interface to connect the data form with a container.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Formable
{
    /**
     * Default param for form mappings.
     */
    const FORM_PARAM = 'general';

    /**
     * Set the form reference to data form pool.
     *
     * @param \Softworx\RocXolid\Forms\Contracts\Form $form
     * @param string $param
     * @return \Softworx\RocXolid\Forms\Contracts\Formable
     */
    public function setForm(Form $form, string $param = self::FORM_PARAM): Formable;

    /**
     * Get all assigned forms
     *
     * @return \Illuminate\Support\Collection
     */
    public function getForms(): Collection;

    /**
     * Retrieve data form instance upon request.
     *
     * @param \Softworx\RocXolid\Http\Requests\CrudRequest $request Incoming request.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable|null $model Form model binding.
     * @param string|null $param Form parameter.
     * @return \Softworx\RocXolid\Forms\Contracts\Form
     * @throws \InvalidArgumentException
     */
    public function getForm(CrudRequest $request, ?Crudable $model = null, ?string $param = null): Form;

    /**
     * Check if the param is already bound.
     *
     * @param string $param
     * @return bool
     */
    public function hasFormAssigned(string $param = self::FORM_PARAM): bool;

    /**
     * Get data form class name mapped to a parameter.
     *
     * @param string $param
     * @return string
     */
    public function getFormMappingType(string $param): string;
}
