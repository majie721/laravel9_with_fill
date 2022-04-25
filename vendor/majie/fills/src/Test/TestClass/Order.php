<?php

namespace Majie\Fills\Test\TestClass;

use JetBrains\PhpStorm\ArrayShape;
use Majie\Fills\Fill\AttributeClass\Decorator;
use Majie\Fills\Fill\AttributeClass\Doc;
use Majie\Fills\Fill\AttributeClass\Enum;
use Majie\Fills\Fill\Proxy;

class Order extends Proxy
{

    #[Doc('状态')]
    #[Enum(StatusEnum::class)]
    public int $status = 1;

    #[Doc('数量')]
    #[Decorator('pow',2)]
    #[Decorator('pow',3)]
    public int $num;

    #[Doc("订单备注")]
    #[Decorator('substr', 2)]
    #[Decorator('trim', '0')]
    #[Decorator([Str::class, 'endFlg'],'&&&')]
    #[Decorator([Str::class, 'startFlg'])]
    public string $desc;

    #[Doc('订单id')]
    public int $id;

    #[Doc('订单编码')]
    public string $order_code;

    #[Doc('是否退件单')]
    public bool $is_return;

    /** @var array  */
    #[ArrayShape(['string'])]
    public array $services;

    #[Doc('商品信息')]
    #[ArrayShape([OrderProduct::class])]
    public array $product;

    #[Doc('状态文本')]
    public string $status_text = '处理中';

    #[Doc('地址信息')]
    public Address $address;


}