<?php

namespace Majie\Fills\Fill\AttributeClass;

#[\Attribute]
class Doc
{
    /**
     * @param string $doc 属性注释
     */
    public function __construct(
        public string $doc
    )
    {

    }
}