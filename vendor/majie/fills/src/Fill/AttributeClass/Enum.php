<?php

namespace Majie\Fills\Fill\AttributeClass;

#[\Attribute]
class Enum
{
    /**
     * @param string $enumClass 属性关联的枚举类
     */
    public function __construct(
        public string $enumClass
    )
    {

    }
}