<?php

namespace Softworx\RocXolid\Forms\Services;

// rocXolid service contracts
use Softworx\RocXolid\Services\Contracts\ConsumerService;
// rocXolid service contracts
use Softworx\RocXolid\Contracts\ServiceConsumer;
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
    /**
     * Service consumer reference.
     *
     * @var \Softworx\RocXolid\Forms\Contracts\Formable
     */
    protected $consumer;

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
    public function setConsumer(ServiceConsumer $consumer): ConsumerService
    {
        // cannot do this more clean coz FormService implements ConsumerService too
        // and ConsumerService requires ServiceConsumer for setConsumer
        // extending ServiceConsumer with Formable and setting Formable arguments doesn't work
        if (!($consumer instanceof Formable)) {
            throw new \InvalidArgumentException(sprintf('Provided service consumer [%s] must implement [%s] interface', get_class($consumer), Formable::class));
        }

        $this->consumer = $consumer;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function createForm(Crudable $model, string $param): Form
    {
        $type = $this->consumer->getFormMappingType($param);

        return $this->form_builder->buildForm($this->consumer, $model, $type, $param);
    }
}
