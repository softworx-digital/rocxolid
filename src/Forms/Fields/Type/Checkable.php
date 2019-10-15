<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class Checkable extends AbstractFormField
{

    /**
     *{@inheritdoc}
     */
    protected $valueProperty = 'checked';

    /**
     *{@inheritdoc}
     */
    protected function getTemplate()
    {
        return $this->type;
    }

    /**
     *{@inheritdoc}
     */
    public function getDefaults()
    {
        return [
            'attr' => ['class' => null, 'id' => $this->getName()],
            'value' => 1,
            'checked' => null
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function isValidValue($value)
    {
        return $value !== null;
    }
}
