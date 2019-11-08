<?php

namespace Softworx\RocXolid\Forms\Validation;

use Carbon\Carbon;
use Illuminate\Validation\Validator as IlluminateValidator;

class Validator extends IlluminateValidator
{
    public function validateOnlyOne(string $attribute, $value, array $parameters): bool
    {
        if ($value === '') {
            return true;
        }

        foreach ($parameters as $parameter) {
            if (($attribute != $parameter) && $this->getValue($parameter)) {
                return false;
            }
        }

        return true;
    }

    public function replaceOnlyOne(string $message, string $attribute, string $rule, array $parameters): string
    {
        $translated = [];

        foreach ($parameters as $p) {
            $translated[] = __(sprintf('validation.attributes.%s', $p));
        }

        return str_replace(':parameters', implode(', ', $translated), $message);
    }

    public function validateClassExists(string $attribute, $value, array $parameters): bool
    {
        return class_exists($value);
    }

    public function validateAge(string $attribute, $value, array $parameters): bool
    {
        return Carbon::now()->diff(Carbon::make($value))->y >= ($parameters[0] ?? 0);
    }

    public function replaceAge(string $message, string $attribute, string $rule, array $parameters): string
    {
        return str_replace(':age', implode(' / ', $parameters), $message);
    }
}
