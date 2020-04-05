<?php

namespace Softworx\RocXolid\Forms\Services\Contracts;

// rocXolid service contracts
use Softworx\RocXolid\Services\Contracts\ConsumerService;
// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\Form;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Serves to retrieve and manipulate forms.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface FormService extends ConsumerService
{
    /**
     * Create data form based on provided parameter which is set back to the created form.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param string $param
     * @return \Softworx\RocXolid\Forms\Contracts\Form
     */
    public function createForm(Crudable $model, string $param): Form;
}
