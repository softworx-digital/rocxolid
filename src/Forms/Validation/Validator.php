<?php

namespace Softworx\RocXolid\Forms\Validation;

use Carbon\Carbon;
use Illuminate\Validation\Validator as IlluminateValidator;
// third-party
use DvK\Laravel\Vat\Facades\Validator as VatValidator;
// rocXolid rendering services
use Softworx\RocXolid\Rendering\Services\RenderingService;

/**
 * Validator extension.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo separate to traits accordiing to validation types
 */
class Validator extends IlluminateValidator
{
    /**
     * Validate that an attribute is the only one defined among attributes defined in parameters.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
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

    /**
     * Replace all place-holders for the only_one rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    public function replaceOnlyOne(string $message, string $attribute, string $rule, array $parameters): string
    {
        $translated = [];

        foreach ($parameters as $p) {
            $translated[] = __(sprintf('validation.attributes.%s', $p));
        }

        return str_replace(':parameters', implode(', ', $translated), $message);
    }

    /**
     * Validate value represents ratio in the form of a fragment.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateRatio(string $attribute, $value, array $parameters): bool
    {
        $match = preg_match('/^([0-9]+)\/([0-9]+)$/', $value, $matches);

        if (!$match) {
            return false;
        }

        list($m, $numerator, $denominator) = $matches;

        return $match && (($numerator < $denominator) || (((int)$numerator === 1) && ((int)$denominator === 1)));
    }

    /**
     * Validate value represents EU VAT number.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateEuvat(string $attribute, $value, array $parameters): bool
    {
        return empty($value) || VatValidator::validate($value);
    }

    /**
     * Validate that value represents fully qualified class name.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateClassExists(string $attribute, $value, array $parameters): bool
    {
        return class_exists($value);
    }

    /**
     * Validate that a date is older than age.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateAge(string $attribute, $value, array $parameters): bool
    {
        return filled($value) && Carbon::make($value)->isPast() && Carbon::now()->diff(Carbon::make($value))->y >= ($parameters[0] ?? 0);
    }

    /**
     * Replace all place-holders for the age rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    public function replaceAge(string $message, string $attribute, string $rule, array $parameters): string
    {
        return str_replace(':age', implode(' / ', $parameters), $message);
    }

    /**
     * Validate that a plain (with stripped tags) text is shorter than maximum.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateMaxplain(string $attribute, $value, array $parameters): bool
    {
        return $this->validateMax($attribute, strip_tags($value), $parameters);
    }

    /**
     * Replace all place-holders for the maxplain rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    public function replaceMaxplain(string $message, string $attribute, string $rule, array $parameters): string
    {
        return str_replace(':max', implode(' ', $parameters), $message);
    }

    /**
     * Validate that a value is (localized) decimal number.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateDecimal(string $attribute, $value, array $parameters): bool
    {
        $nf = new \NumberFormatter(app()->getLocale(), \NumberFormatter::DECIMAL);
        $nf->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 10);

        $grouping_separator = $nf->getSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL);
        // this is probably a bug in PHP - for eg. sk_SK locale, the grouping symbol is ord(194),
        // not ord(32) - space
        $grouping_separator = (ord($grouping_separator) === 194) ? chr(32) : $grouping_separator;

        /*
        $int_pattern = sprintf(
            '/^(0|(-?[1-9][0-9]{0,%s}(([0-9]*)|(%s[0-9]{%s})*)))$/',
            $nf->getAttribute(\NumberFormatter::GROUPING_SIZE) - 1,
            $grouping_separator,
            $nf->getAttribute(\NumberFormatter::GROUPING_SIZE)
        );
        */

        $int_pattern = '/^(0|(-?[1-9][0-9]*))$/';

        $frac_pattern = sprintf(
            '/^[0-9]{%s,%s}$/',
            $nf->getAttribute(\NumberFormatter::MIN_FRACTION_DIGITS),
            $nf->getAttribute(\NumberFormatter::MAX_FRACTION_DIGITS)
        );

        $decimal_separator = $nf->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);

        list($int, $frac) = array_pad(explode($decimal_separator, $value, 2), 2, 0);

        return preg_match($int_pattern, $int) && preg_match($frac_pattern, $frac);
    }

    /**
     * Validate the size of an (localized) decimal attribute is greater than a minimun value.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateMinDecimal(string $attribute, $value, array $parameters): bool
    {
        $value = str_replace(',', '.', $value);
        $value = str_replace(' ', '', $value);
        $value = (float)$value;

        return $value >= $parameters[0];
    }

    /**
     * Replace all place-holders for the min decimal rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    public function replaceMinDecimal(string $message, string $attribute, string $rule, array $parameters): string
    {
        // @todo hotfixed formatting
        return str_replace(':min_decimal', number_format($parameters[0], 2, ',', ''), $message);
    }

    /**
     * Validate the size of an (localized) decimal attribute is less than a maximum value.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateMaxDecimal(string $attribute, $value, array $parameters): bool
    {
        // @todo hotfixed
        $value = str_replace(',', '.', $value);
        $value = str_replace(' ', '', $value);
        $value = (float)$value;

        return $value <= $parameters[0];
    }

    /**
     * Replace all place-holders for the max decimal rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    public function replaceMaxDecimal(string $message, string $attribute, string $rule, array $parameters): string
    {
        // @todo hotfixed formatting
        return str_replace(':max_decimal', number_format($parameters[0], 2, ',', ''), $message);
    }

    /**
     * Validate the size of an (localized) decimal attribute is greater than a minimun value.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validatePositiveDecimal(string $attribute, $value, array $parameters): bool
    {
        $value = str_replace(',', '.', $value);
        $value = str_replace(' ', '', $value);
        $value = (float)$value;

        return $value > 0;
    }

    /**
     * Validate that a (localized) decimal number is greater than another attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateGtdecimal(string $attribute, $value, array $parameters): bool
    {
        return $this->validateGt($attribute, $this->getParsedDecimalNumber($value), $parameters);
    }

    /**
     * Replace all place-holders for the gtdecimal rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    public function replaceGtdecimal(string $message, string $attribute, string $rule, array $parameters): string
    {
        return str_replace(':value', $parameters[0], $message);
    }

    /**
     * Validate that a value is (localized) decimal latitude.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateLatitude(string $attribute, $value, array $parameters): bool
    {
        return preg_match('/^([-]?(([0-8]?[0-9])(\.(\d+))?)|(90(\.0+)?))$/', $value);
    }

    /**
     * Validate that a value is (localized) decimal longitude.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateLongitude(string $attribute, $value, array $parameters): bool
    {
        return preg_match('/^([-]?((((1[0-7][0-9])|([0-9]?[0-9]))(\.(\d+))?)|180(\.0+)?))$/', $value);
    }

    /**
     * Replace all place-holders for the after rule.
     * @todo "hotfixed" - improve overall date handling according to localization
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    protected function replaceAfter($message, $attribute, $rule, $parameters)
    {
        return str_replace(':date', Carbon::make($parameters[0] ?? 'now')->format('j.n.Y'), $message);
    }

    /**
     * Replace all place-holders for the after_or_equal rule.
     * @todo "hotfixed" - improve overall date handling according to localization
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    protected function replaceAfterOrEqual($message, $attribute, $rule, $parameters)
    {
        return str_replace(':date', Carbon::make($parameters[0] ?? 'now')->format('j.n.Y'), $message);
    }

    /**
     * Replace all place-holders for the before_or_equal rule.
     * @todo "hotfixed" - improve overall date handling according to localization
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    protected function replaceBefore($message, $attribute, $rule, $parameters)
    {
        return str_replace(':date', Carbon::make($parameters[0] ?? 'now')->format('j.n.Y'), $message);
    }

    /**
     * Replace all place-holders for the before_or_equal rule.
     * @todo "hotfixed" - improve overall date handling according to localization
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    protected function replaceBeforeOrEqual($message, $attribute, $rule, $parameters)
    {
        return str_replace(':date', Carbon::make($parameters[0] ?? 'now')->format('j.n.Y'), $message);
    }

    /**
     * Validate that a value is syntactically correct blade template.
     * @todo so far serves only for Softworx\RocXolid\Communication\Models\Contracts\Sendable
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateBladeTemplate(string $attribute, $value, array $parameters): bool
    {
        list($model_type, $model_id) = $parameters;

        $model = $model_type::find($model_id);
        $model->setEvent(app($model->event_type));
        $variables = $model->getEvent()->getSendableVariables();

        $value = str_replace('-&gt;', '->', $value);

        try {
            RenderingService::render($value, $variables);
        } catch (\Throwable $e) {
            $this->setException($e);

            return false;
        }

        return true;
    }

    /**
     * Replace all place-holders for the blade_template rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    public function replaceBladeTemplate(string $message, string $attribute, string $rule, array $parameters): string
    {
        return str_replace(':error', $this->exception->getMessage(), $message);
    }

    /**
     * Validate that a value is a IBAN.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateIban(string $attribute, $value, array $parameters)
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

    /**
     * IBAN length helper.
     *
     * @param string $iban
     * @return int|null
     */
    private function getIbanLength(string $iban): ?int
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

    /**
     * Parse decimal number representation according to app locale.
     *
     * @param string $value
     * @return string
     */
    private function getParsedDecimalNumber(string $value): string
    {
        $nf = new \NumberFormatter(app()->getLocale(), \NumberFormatter::DECIMAL);

        // $value = preg_replace($nf->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL), '.', $value);
        // $value = preg_replace($nf->getSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL), '', $value);

        return (string)$nf->parse($value);
    }
}
