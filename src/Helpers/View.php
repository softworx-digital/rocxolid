<?php

namespace Softworx\RocXolid\Helpers;

/**
 * Wrapper class for View processing helper functions.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class View
{
    /**
     * Creates the DOM ID for given component / param.
     *
     * @param mixed $param Component or parameter to get DOM ID for.
     * @param array $additional Additional params to add to the DOM ID. They will be separated by dash (-) sign.
     * @return string
     */
    public static function domId($param, ...$additional): string
    {
        $id = md5(is_object($param) ? get_class($param) : $param);

        if (is_array($additional)) {
            foreach ($additional as $add) {
                $id .= str_replace(':', '-', sprintf('-%s', $add));
            }
        }

        return $id;
    }

    /**
     * Creates the DOM ID for given component / param prefixed with hash (#) sign.
     *
     * @param mixed $param Component or parameter to get DOM ID for.
     * @param array $additional Additional params to add to the DOM ID. They will be separated by dash.
     * @return string
     */
    public static function domIdHash($param, ...$additional): string
    {
        return sprintf('#%s', static::domId($param, ...$additional));
    }

    /**
     * Truncates a text to given length in a non-word breaking manner.
     * If text is longer then given length, the truncation occurs resulting in a text with following '...'.
     *
     * @param string $text Text to truncate.
     * @param int $length Length to which the text will be truncated.
     * @return string
     */
    public static function truncate(string $text, int $length): string
    {
        return strtok(wordwrap(strip_tags($text), $length, "...\n"), "\n");
    }
}
