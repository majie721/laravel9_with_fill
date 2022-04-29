<?php
namespace Majie\Fills\Fill\Library\Functions;


use Illuminate\Support\Str;

class Func
{
    /**
     * 是否标量
     * @param string $type
     * @return bool
     */
    public static function isScalar(string $type): bool
    {
        return in_array($type, ['int', 'bool', 'string', 'float'], true);
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isClass(string $type): bool
    {
        return !in_array($type, ['int', 'bool', 'string', 'float','array'], true);
    }

}
