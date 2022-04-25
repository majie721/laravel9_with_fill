<?php

namespace App\Helpers;

class Common
{
    /**
     * @param mixed $val
     * @return bool
     */
    public static function isTrueFloat(mixed $val):bool
    {
        $pattern = '/^[+-]?(\d*\.\d+([eE]?[+-]?\d+)?|\d+[eE][+-]?\d+)$/';

        return (!is_bool($val) && (is_float($val) || preg_match($pattern, trim($val))));
    }
}
