<?php

namespace App\Http\Nemo\Controllers\Beans;

use JetBrains\PhpStorm\ArrayShape;
use LaravelNemo\AttributeClass\Doc;
use LaravelNemo\Nemo;

class JsonModelReq extends Nemo
{
    #[ArrayShape([JsonNode::class])]
    public array $list;

    #[Doc('命名空间',true)]
    public ?string $namespace = "App\\Beans";

    /** @var string|null  */
    #[Doc('类名',true)]
    public ?string $className = 'Bean';


}
