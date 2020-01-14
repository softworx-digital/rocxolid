<?php

namespace Softworx\RocXolid\Forms\Validation;

use App;
use Carbon\Carbon;
use Illuminate\Validation\Validator as IlluminateValidator;
// rocXolid services
use Softworx\RocXolid\Services\ViewService;

/**
 *
 */
class Validator extends IlluminateValidator
{
    protected $exception = null;

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

    /**
     * @todo: so far serves only for Softworx\RocXolid\Communication\Contracts\Sendable
     */
    public function validateBladeTemplate(string $attribute, $value, array $parameters): bool
    {
        list($model_type, $model_id) = $parameters;

        $model = $model_type::find($model_id);
        $model->setEvent(App::make($model->event_type));
        $variables = $model->getEvent()->getSendableVariables();

        $value = str_replace('-&gt;', '->', $value);

        try {
            ViewService::render($value, $variables);
        } catch (\Exception $e) {
            $this->exception = $e;

            return false;
        }

        return true;
    }

    public function replaceBladeTemplate(string $message, string $attribute, string $rule, array $parameters): string
    {
        return str_replace(':error', $this->exception->getMessage(), $message);
    }

    public function validateIban(string $attribute, $value)
    {
        // build replacement arrays
        $iban_replace_chars = range('A', 'Z');
        foreach (range(10, 35) as $tempvalue) {
            $iban_replace_values[] = strval($tempvalue);
        }
        // prepare string
        $tempiban = strtoupper($value);
        $tempiban = str_replace(' ', '', $tempiban);
        // check iban length
        if ($this->getIbanLength($tempiban) != strlen($tempiban)) {
            return false;
        }
        // build checksum
        $tempiban = substr($tempiban, 4).substr($tempiban, 0, 4);
        $tempiban = str_replace($iban_replace_chars, $iban_replace_values, $tempiban);
        $tempcheckvalue = intval(substr($tempiban, 0, 1));
        for ($strcounter = 1; $strcounter < strlen($tempiban); $strcounter++) {
            $tempcheckvalue *= 10;
            $tempcheckvalue += intval(substr($tempiban, $strcounter, 1));
            $tempcheckvalue %= 97;
        }
        // only modulo 1 is iban
        return $tempcheckvalue == 1;
    }

    private function getIbanLength($iban)
    {
        $countrycode = substr($iban, 0, 2);
        $lengths = [
            'AL' => 28,
            'AD' => 24,
            'AT' => 20,
            'AZ' => 28,
            'BH' => 22,
            'BE' => 16,
            'BA' => 20,
            'BR' => 29,
            'BG' => 22,
            'CR' => 21,
            'HR' => 21,
            'CY' => 28,
            'CZ' => 24,
            'DK' => 18,
            'DO' => 28,
            'TL' => 23,
            'EE' => 20,
            'FO' => 18,
            'FI' => 18,
            'FR' => 27,
            'GE' => 22,
            'DE' => 22,
            'GI' => 23,
            'GR' => 27,
            'GL' => 18,
            'GT' => 28,
            'HU' => 28,
            'IS' => 26,
            'IE' => 22,
            'IL' => 23,
            'IT' => 27,
            'JO' => 30,
            'KZ' => 20,
            'XK' => 20,
            'KW' => 30,
            'LV' => 21,
            'LB' => 28,
            'LI' => 21,
            'LT' => 20,
            'LU' => 20,
            'MK' => 19,
            'MT' => 31,
            'MR' => 27,
            'MU' => 30,
            'MC' => 27,
            'MD' => 24,
            'ME' => 22,
            'NL' => 18,
            'NO' => 15,
            'PK' => 24,
            'PS' => 29,
            'PL' => 28,
            'PT' => 25,
            'QA' => 29,
            'RO' => 24,
            'SM' => 27,
            'SA' => 24,
            'RS' => 22,
            'SK' => 24,
            'SI' => 19,
            'ES' => 24,
            'SE' => 24,
            'CH' => 21,
            'TN' => 24,
            'TR' => 26,
            'AE' => 23,
            'GB' => 22,
            'VG' => 24,
            'DZ' => 24,
            'AO' => 25,
            'BJ' => 28,
            'BF' => 27,
            'BI' => 16,
            'CM' => 27,
            'CV' => 25,
            'IR' => 26,
            'CI' => 28,
            'MG' => 27,
            'ML' => 28,
            'MZ' => 25,
            'SN' => 28,
            'UA' => 29
        ];
        return isset($lengths[$countrycode]) ? $lengths[$countrycode] : false;
    }
}
