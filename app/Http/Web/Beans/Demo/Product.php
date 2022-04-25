<?php

namespace App\Http\Web\Beans\Demo;

use JetBrains\PhpStorm\ArrayShape;
use Majie\Fills\Fill\AttributeClass\Doc;
use Majie\Fills\Fill\Proxy;

class Product extends Proxy
{
    #[Doc('商品id')]
    public int $id;

    #[Doc('商品名称')]
    public string $name;

    #[Doc('商品价格')]
    public float $price;

    #[Doc('商品属性')]
    #[ArrayShape(['string'])]
    public array $attrs;

}
