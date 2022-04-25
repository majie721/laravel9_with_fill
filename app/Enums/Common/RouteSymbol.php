<?php

namespace App\Enums\Common;

use Majie\Fills\Fill\Interface\IBaseEnum;
use Majie\Fills\Fill\Traits\EnumArrayAccessTrait;
use Majie\Fills\Fill\Traits\EnumTrait;

enum RouteSymbol:string implements IBaseEnum, \ArrayAccess
{
    use EnumTrait, EnumArrayAccessTrait;

    case WEB = 'web';
    case API = 'api';
}
