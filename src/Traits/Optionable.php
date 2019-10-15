<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Contracts\Optionable as OptionableContract;

/**
 * Enables object to have options assigned dynamically.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Optionable
{
    /**
     * @var \Illuminate\Support\Collection Options container.
     */
    private $options;

    /**
     * {@inheritdoc}
     */
    public function setOption(string $option, mixed $value): OptionableContract
    {
        $this->getOptions()->put($option, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options): OptionableContract
    {
        $this->options = new Collection($options);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOption(string $option, $default = null)
    {
        $options = $this->getOptions();
        $parts = explode('.', $option);

        while (($key = array_shift($parts)) && $options->has($key) && !empty($parts)) {
            $options = new Collection($options->get($key));
        }

        if ($options->has($key)) {
            return $options->get($key);
        } elseif (!is_null($default)) {
            return $default;
        } else {
            throw new \InvalidArgumentException(sprintf("Invalid option [%s] requested, available:\n%s", $option, implode("\n", $this->getOptionsKeys())));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): Collection
    {
        if (is_null($this->options)) {
            $this->options = new Collection();
        }

        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function mergeOptions($options): OptionableContract
    {
        //$this->options = $this->getOptions()->merge($new_options); // doesn't deep merge
        $this->options = new Collection(array_replace_recursive($this->getOptions()->toArray(), $options)); // @tu bude problem, ak na konci bude objekt

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeOption($option): OptionableContract
    {
        $options = $this->getOptions()->toArray();

        if (array_has($options, $option)) {
            array_forget($options, $option);
        } else {
            throw new \UnderflowException(sprintf('Option [%s] is not set', $option));
        }

        $this->options = new Collection($options);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionsKeys(): array
    {
        return array_keys(array_dot($this->getOptions()->all()));
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption($option): bool
    {
        return $this->getOptions()->has($option);
    }
}
