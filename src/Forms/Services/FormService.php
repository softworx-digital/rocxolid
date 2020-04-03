<?php

namespace Softworx\RocXolid\Forms\Services;

// use Softworx\RocXolid\Forms\FormBuilder;

// rocXolid service contracts
use Softworx\RocXolid\Services\Contracts\ConsumerService;
// rocXolid service contracts
use Softworx\RocXolid\Contracts\ServiceConsumer;
// rocXolid table contracts
use Softworx\RocXolid\Forms\Contracts\Formable;
use Softworx\RocXolid\Forms\Services\Contracts\FormService as FormServiceContract;

/**
 * Service to retrieve forms.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class FormService implements FormServiceContract
{
    protected $consumer;

    public function setConsumer(ServiceConsumer $consumer): ConsumerService
    {
        if (!($consumer instanceof Formable)) {
            throw new \InvalidArgumentException(sprintf('Provided service consumer [%s] must implement [%s] interface', get_class($consumer), Formable::class));
        }

        $this->consumer = $consumer;

        return $this;
    }

    /**
     * Route reference.
     *
     * @var FormBuilder
     */
    // protected $form_builder;

    /**
     * Contructor.
     *
     * @param FormBuilder $form_builder Form builder.
     * @return FormService
     */
    // public function __construct(FormBuilder $form_builder)
    // {
    //     $this->form_builder = $form_builder;
    // }

    /**
     * Returns form.
     *
     * @return \Softworx\RocXolid\Forms\AbstractForm
     */
    // public function getForm($action, $params = null)
    // {
    //     dd(__METHOD__);
    //     return null;
    //     //return $this->form_builder;
    // }
}
