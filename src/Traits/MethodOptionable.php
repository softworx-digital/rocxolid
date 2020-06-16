<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Support\Str;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Optionable as OptionableContract;

/**
 * Trait to enable dynamic options setting with class mathod.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait MethodOptionable
{
    use Optionable;

    /**
     * Set option value.
     *
     * @param string $option
     * @param mixed $value
     * @return \Softworx\RocXolid\Contracts\Optionable
     */
    public function setOption(string $option, $value): OptionableContract
    {
        $method = Str::camel(sprintf('set_%s', str_replace('-', '_', $option)));

        if (!method_exists($this, $method)) {
            throw new \InvalidArgumentException(sprintf('Required method [%s] does not exist in [%s]', $method, get_class($this)));
        }

        $this->$method($value);

        return $this;
    }

    /**
     * Set several options at once.
     *
     * @param array $options
     * @return \Softworx\RocXolid\Contracts\Optionable
     */
    public function setOptions(array $options): OptionableContract
    {
        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }

        return $this;
    }
}
