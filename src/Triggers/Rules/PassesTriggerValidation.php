<?php

namespace Softworx\RocXolid\Triggers\Rules;

use Illuminate\Contracts\Validation\Rule;
// rocXolid forms
use Softworx\RocXolid\Forms\AbstractCrudForm;
// rocXolid trigger contracts
use Softworx\RocXolid\Triggers\Contracts\Trigger;

/**
 * Rule that checks if the submitted form data conforms underlying Trigger needs.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class PassesTriggerValidation implements Rule
{
    private $form;

    private $trigger;

    public function __construct(AbstractCrudForm $form)
    {
        $this->form = $form;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (is_null($value)) {
            return true;
        }

        $this->trigger = app($value);

        if (!($this->trigger instanceof Trigger)) {
            throw new \InvalidArgumentException(sprintf('Trigger type expected, [%s] given', get_class($value)));
        }

        return $this->trigger->validateAssignmentData(collect($this->form->getInput()), $attribute);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->trigger->assignmentValidationErrorMessage($this->form->getController(), collect($this->form->getInput()));
    }
}
