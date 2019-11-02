<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Support\Arr;
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
     * 
     * Has to be protected to enable direct override in specific class.
     */
    protected $options;

    /**
     * {@inheritdoc}
     */
    public function setOption(string $option, $value): OptionableContract
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

        if (is_array($this->options)) {
            $this->options = collect($this->options);
        }

        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function mergeOptions(array $options): OptionableContract
    {
        //$this->options = $this->getOptions()->merge($new_options); // doesn't deep merge
        $this->options = new Collection(array_replace_recursive($this->getOptions()->toArray(), $options)); // @todo: problem possible if object at the end

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeOption(string $option, bool $report = false): OptionableContract
    {
        $options = $this->getOptions()->toArray();

        if (Arr::has($options, $option)) {
            Arr::forget($options, $option);
        } elseif ($report) {
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
        return array_keys(Arr::dot($this->getOptions()->all()));
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption(string $option): bool
    {
        return $this->getOptions()->has($option);
    }
}
