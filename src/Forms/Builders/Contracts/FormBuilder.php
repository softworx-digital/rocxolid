<?php

namespace Softworx\RocXolid\Forms\Builders\Contracts;

// rocXolid form contracts
use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Forms\Contracts\Formable;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Form builder and dependencies connector.
 * Provides convenient way to build a data form.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface FormBuilder
{
    /**
     * Get instance of the form which can be modified.
     *
     * @param \Softworx\RocXolid\Forms\Contracts\Formable $container Form container.
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model Assigned model.
     * @param string $type Form class.
     * @param string $param Form parameter.
     * @param array $custom_options Custom form options.
     * @param array $data Custom form options.
     * @return \Softworx\RocXolid\Forms\Contracts\Form
     */
    public function buildForm(Formable $container, Crudable $model, string $type, string $param, array $custom_options = [], array $data = []): Form;
}
