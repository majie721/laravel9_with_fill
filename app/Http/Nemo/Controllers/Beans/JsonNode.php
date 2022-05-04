<?php

namespace App\Http\Nemo\Controllers\Beans;

use JetBrains\PhpStorm\ArrayShape;
use LaravelNemo\Nemo;

class JsonNode extends Nemo
{
    /** name  */
    public string $name;

    /** int,float,string,bool,array,object */
    public string $type;

    /**  */
    public ?string $desc=null;

    /** 元素层级 */
    public int $depth;

    #[ArrayShape([JsonNode::class])]
    public array $children;

    /** mock */
    public ?string $mock = null;

    /** 数组类型 */
    public ?string $array_type = null;

    /** @var string  */
    public string $key;
}
