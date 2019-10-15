<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Softworx\RocXolid\Contracts\Optionable as OptionableContract;

trait MethodOptionable
{
    use Optionable;

    public function setOption($option, $value): OptionableContract
    {
        $method = camel_case(sprintf('set_%s', str_replace('-', '_', $option)));

        if (!method_exists($this, $method)) {
            throw new \InvalidArgumentException(sprintf('Required method [%s] does not exist in [%s] field [%s]', $method, get_class($this), $this->name));
        }

        $this->$method($value);

        return $this;
    }

    public function setOptions($options): OptionableContract
    {
        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }

        return $this;
    }
}
