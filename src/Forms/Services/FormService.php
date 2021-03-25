<?php

namespace Softworx\RocXolid\Forms\Services;

// rocXolid service contracts
use Softworx\RocXolid\Services\Contracts\ServiceConsumer;
// rocXolid service traits
use Softworx\RocXolid\Services\Traits\HasServiceConsumer;
// rocXolid form contracts
use Softworx\RocXolid\Forms\Services\Contracts\FormService as FormServiceContract;
use Softworx\RocXolid\Forms\Builders\Contracts\FormBuilder;
use Softworx\RocXolid\Forms\Contracts\Formable;
use Softworx\RocXolid\Forms\Contracts\Form;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Service to retrieve and manipulate forms.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class FormService implements FormServiceContract
{
    use HasServiceConsumer;

    /**
     * Form builder reference.
     *
     * @var \Softworx\RocXolid\Forms\Builders\Contracts\FormBuilder
     */
    protected $form_builder;

    /**
     * Constructor.
     *
     * @param \Softworx\RocXolid\Forms\Builders\Contracts\FormBuilder
     * @return \Softworx\RocXolid\Forms\Services\FormService
     */
    public function __construct(FormBuilder $form_builder)
    {
        $this->form_builder = $form_builder;
    }

    /**
     * {@inheritDoc}
     */
    public function createForm(Crudable $model, string $param, ?string $type = null, array $custom_options = [], array $data = []): Form
    {
        $type = $type ?? $this->consumer->getFormMappingType($param);

        return $this->form_builder->buildForm($this->consumer, $model, $type, $param, $custom_options, $data);
    }

    /**
     * {@inheritDoc}
     */
    protected function validateServiceConsumer(ServiceConsumer $consumer): bool
    {
        return ($consumer instanceof Formable);
    }
}
