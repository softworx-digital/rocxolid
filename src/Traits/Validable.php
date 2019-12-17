<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Support\MessageBag;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Contracts\Validation\Validator;
use Softworx\RocXolid\Contracts\Validable as ValidableContract;

/**
 * Enables object to validate data.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Validable
{
    protected $validator_factory;

    protected $validator = null;

    protected $validation_data = null;

    protected $validation_rules = null;

    protected $validation_messages = [];

    protected $validation_custom_attributes = [];

    public function validate(array $validation_rules = [], array $messages = []): ValidableContract
    {
        $validation = $this->getRequest()->getFieldsValidation($this->getFormFields());

        $rules = array_merge($validation['rules'], $validation_rules);
        $messages = array_merge($validation['error_messages'], $messages);

        $this->validator = $this->getValidatorFactory()->make($this->getValidationData($validation), $rules, $messages);
        $this->validator->setAttributeNames($validation['attributes']);

        return $this;
    }

    public function validateGroup(string $group, array $validation_rules = [], array $messages = []): ValidableContract
    {
        $validation = $this->getRequest()->getFieldsValidation($this->getFormFields($group));

        $rules = array_merge($validation['rules'], $validation_rules);
        $messages = array_merge($validation['error_messages'], $messages);

        $this->validator = $this->getValidatorFactory()->make($this->getRequest()->only($validation['attributes']), $rules, $messages);
        $this->validator->setAttributeNames($validation['attributes']);

        return $this;
    }

    public function setValidationData(array $validation_data): ValidableContract
    {
        $this->validation_data = $validation_data;

        return $this;
    }

    public function getValidationData(): array
    {
        if (is_null($this->validation_data)) {
            // @todo: this could probably be $this->getRequest()->only($validation['attributes'])
            $this->validation_data = $this->getRequest()->all();
        }

        return $this->validation_data;
    }

    public function setValidationRules(array $validation_rules): ValidableContract
    {
        $this->validation_rules = $validation_rules;

        return $this;
    }

    public function setValidationMessages(array $validation_messages): ValidableContract
    {
        $this->validation_messages = $validation_messages;

        return $this;
    }

    public function setValidationCustomAttributes(array $validation_custom_attributes): ValidableContract
    {
        $this->validation_custom_attributes = $validation_custom_attributes;

        return $this;
    }

    public function setValidatorFactory(ValidatorFactory $validator_factory): ValidableContract
    {
        $this->validator_factory = $validator_factory;

        return $this;
    }

    public function getValidatorFactory(): ValidatorFactory
    {
        if (!$this->hasValidatorFactory()) {
            throw new \UnderflowException(sprintf('No validator factory set in [%s]', get_class($this)));
        }

        return $this->validator_factory;
    }

    public function setValidator(Validator $validator): ValidableContract
    {
        $this->validator = $validator;

        return $this;
    }

    public function getValidator(): Validator
    {
        if (is_null($this->validator)) {
            if (is_null($this->validation_data)) {
                throw new \UnderflowException('Validation data not set');
            }

            if (is_null($this->validation_rules)) {
                throw new \UnderflowException('Validation rules not set');
            }

            $this->validator = $this->getValidatorFactory()->make(
                $this->validation_data,
                $this->validation_rules,
                $this->validation_messages,
                $this->validation_custom_attributes
            );
        }

        return $this->validator;
    }

    /**
     * @todo Really this way?
     */
    public function wasValidated(): bool
    {
        return $this->validator && ($this->validator instanceof Validator);
    }

    /**
     * Check if the form is valid.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        if (!$this->wasValidated()) {
            $this->validate();
        }

        $is_valid = !$this->validator->fails();

        //$this->getFormHelper()->alterValid($this, $this, $is_valid);

        return $is_valid;
    }

    public function getValidationErrors(): MessageBag
    {
        if (!$this->wasValidated()) {
            throw new \InvalidArgumentException(sprintf('Form %s was not validated. To validate it, call "isValid" method before retrieving the errors', get_class($this)));
        }

        return $this->validator->getMessageBag();
    }

    public function getErrors(): MessageBag
    {
        $errors = $this->wasValidated()
                ? $this->getValidationErrors()
                //: $this->getRequest()->session()->get('errors', new ViewErrorBag())->toArray();
                : $this->getRequest()->session()->get($this->getSessionParam('errors'), new MessageBag());

        return $errors;
    }

    public function hasValidatorFactory(): bool
    {
        return isset($this->validator_factory);
    }
}
