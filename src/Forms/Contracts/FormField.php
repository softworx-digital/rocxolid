<?php

namespace Softworx\RocXolid\Forms\Contracts;

use Illuminate\Database\Eloquent\Model;
use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Forms\FormHelper;

// @todo define properly
interface FormField
{
    const SINGLE_DATA_PARAM = '_data';

    const ARRAY_DATA_PARAM = '_datagroup';

    public function getTitle(): string;
    /*
    public function render(array $options = [], $show_label = true, $show_field = true, $show_error = true);

    public function getName();

    public function setName($name);

    public function getOptions();

    public function getOption($option, $default = null);

    public function setOptions($options);

    public function setOption($name, $value);

    public function getType();

    public function setType($type);

    public function getParent();

    public function isRendered();

    public function getRealName();

    public function setValue($value);

    public function disable();

    public function enable();

    public function getValidationRules();

    public function getAllAttributes();

    public function getValue($default = null);

    public function getDefaultValue($default = null);
    */
}
