<?php

namespace App\Http\Web\Beans\Demo;

use App\Http\Enums\DemoStatus;
use Illuminate\Support\Facades\Validator;
use JetBrains\PhpStorm\ArrayShape;
use Majie\Fills\Fill\AttributeClass\Doc;
use Majie\Fills\Fill\AttributeClass\Enum;
use Majie\Fills\Fill\Proxy;

class Demo extends Proxy
{

    #[Doc('订单ID')]
    public int $id;

    #[Doc('订单号')]
    public int $code;

    #[Doc('订单创建时间')]
    public string $created_at;

    #[Doc('订单状态')]
    #[Enum(DemoStatus::class)]
    public int $status;

    #[ArrayShape([Product::class])]
    public array $product;



}
