<?php

namespace Majie\Fills\Test\TestClass;


use Majie\Fills\Fill\Interface\IBaseEnum;
use Majie\Fills\Fill\Traits\EnumArrayAccessTrait;
use Majie\Fills\Fill\Traits\EnumTrait;

enum StatusEnum:int implements IBaseEnum, \ArrayAccess
{
    use EnumTrait, EnumArrayAccessTrait;

    case DRAFT=1;

    case PROCESSING=2;

    case COMPLETE=3;

    public function label(): string
    {
        return match($this) {
            self::DRAFT => '草稿',
            self::PROCESSING => '处理中',
            self::COMPLETE => '已完成',
        };
    }
}
