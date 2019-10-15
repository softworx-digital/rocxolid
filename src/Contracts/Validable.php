<?php

namespace Softworx\RocXolid\Contracts;

use Illuminate\Support\MessageBag;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Contracts\Validation\Validator;

/**
 * Enables object to validate data.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Validable
{
    /**
     * Validate the data.
     *
     * @param array $validation_rules Additional rules to be used for validation.
     * @param array $messages Additional error messages to be used as a result of unsuccessful validation.
     * @return \Softworx\RocXolid\Contracts\Validable
     */
    public function validate(array $validation_rules = [], array $messages = []): Validable;

    /**
     * Set the data to be validated.
     *
     * @param array $validation_data Data to be validated
     * @return \Softworx\RocXolid\Contracts\Validable
     */
    public function setValidationData(array $validation_data): Validable;

    /**
     * Set the rules to be used for validation.
     *
     * @param array $validation_rules Rules to be used for validation.
     * @return \Softworx\RocXolid\Contracts\Validable
     */
    public function setValidationRules(array $validation_rules): Validable;

    /**
     * Set the messages to be used as a result of unsuccessful validation.
     *
     * @param array $validation_messages Messages to be used as a result of unsuccessful validation.
     * @return \Softworx\RocXolid\Contracts\Validable
     */
    public function setValidationMessages(array $validation_messages): Validable;

    /**
     * Set custom attributes for validator.
     *
     * @param array $validation_custom_attributes Custom attributes for validator.
     * @return \Softworx\RocXolid\Contracts\Validable
     */
    public function setValidationCustomAttributes(array $validation_custom_attributes): Validable;

    /**
     * Set the factory for validator.
     *
     * @param \Illuminate\Contracts\Validation\Factory $validator_factory Factory for validator.
     * @return \Softworx\RocXolid\Contracts\Validable
     */
    public function setValidatorFactory(ValidatorFactory $validator_factory): Validable;

    /**
     * Set the validator to be used for validation.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator Validator to be used for validation.
     * @return \Softworx\RocXolid\Contracts\Validable
     */
    public function setValidator(Validator $validator): Validable;

    /**
     * Return the validator factory.
     *
     * @return \Illuminate\Contracts\Validation\Factory
     * @throws \UnderflowException If no factory is set.
     */
    public function getValidatorFactory(): ValidatorFactory;

    /**
     * Return the validator. Create one using the validator factory if not set.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     * @throws \UnderflowException If the validator is not set and no validation data is set.
     * @throws \UnderflowException If the validator is not set and no validation rules are set.
     * @throws \UnderflowException If the validator is not set and no factory is set.
     */
    public function getValidator(): Validator;

    /**
     * Check if the data was validated.
     *
     * @return bool
     */
    public function wasValidated(): bool;

    /**
     * Validate the data and check successful validation.
     *
     * @return bool
     */
    public function isValid(): bool;

    /**
     * Get validation errors.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function getValidationErrors(): MessageBag;

    /**
     * @todo what is this needed for ???
     */
    public function getErrors(): MessageBag;

    /**
     * Check if validator factory is set.
     *
     * @return bool
     */
    public function hasValidatorFactory(): bool;
}
