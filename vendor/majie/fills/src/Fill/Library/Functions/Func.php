<?php
namespace Majie\Fills\Fill\Library\Functions;


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
}
