<?php

namespace Softworx\RocXolid\Helpers;

/**
 * Wrapper class for string processing helper functions.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class Text
{
    /**
     * Calculates the percentual similarity between strings.
     *
     * @param string $str1 First string.
     * @param string $str2 Second string.
     * @return float
     */
    public static function similarTexts(string $str1, string $str2): float
    {
        $levenshtein = self::levenshtein($str1, $str2) * 2;
        $similarity = 100 - ($levenshtein / (mb_strlen($str1) + mb_strlen($str2))) * 100;

        return $similarity;
    }

    /**
     * Calculates the Levenshtein distance between strings.
     *
     * @param string $str1 First string.
     * @param string $str2 Second string.
     * @return int
     */
    public static function levenshtein(string $str1, string $str2): int
    {
        $length1 = mb_strlen($str1, 'UTF-8');
        $length2 = mb_strlen($str2, 'UTF-8');

        if ($length1 < $length2) {
            return self::levenshtein($str2, $str1);
        } elseif ($length1 == 0) {
            return $length2;
        } elseif ($str1 === $str2) {
            return 0;
        }

        $prevRow = range(0, $length2);
        $currentRow = [];

        for ($i = 0; $i < $length1; $i++) {
            $currentRow = [];
            $currentRow[0] = $i + 1;
            $c1 = mb_substr($str1, $i, 1, 'UTF-8') ;

            for ($j = 0; $j < $length2; $j++) {
                $c2 = mb_substr($str2, $j, 1, 'UTF-8');
                $insertions = $prevRow[$j+1] + 1;
                $deletions = $currentRow[$j] + 1;
                $substitutions = $prevRow[$j] + (($c1 != $c2) ? 1 : 0);
                $currentRow[] = min($insertions, $deletions, $substitutions);
            }

            $prevRow = $currentRow;
        }

        return $prevRow[$length2];
    }
}
