<?php

namespace Majie\Fills\Test\TestClass;

use Majie\Fills\Fill\AttributeClass\Decorator;
use Majie\Fills\Fill\AttributeClass\Doc;
use Majie\Fills\Fill\Proxy;

class OrderProduct extends Proxy
{
    protected $stopOnFirstFailure = false;

    #[Doc('商品id')]
    public int $id;

    #[Doc('sku')]
    public string $sku;

    #[Doc('商品名称')]
    public string $title;

    #[Doc('数量')]
    #[Decorator('pow',2)]
    #[Decorator('pow',2)]
   // #[Decorator('sqrt')]
    public string $num;

    #[Doc('创建时间')]
    #[Decorator('strtotime')]
    //#[Decorator('date','H:i:s Y-m-d')]
    public string $created_at;


    public function rules()
    {
        return [
            'id'=>"integer|required|min:1|max:100",
            'sku'=>"string|required|min:1|max:10|starts_with:sku,MJ",
        ];
    }

    public function messages()
    {
        return [
            'id.required' => ':attribute is required',
            'id.integer' => ':attribute message must be int',
        ];
     }

     public function attributes()
     {
         return [
             'id'=>'Product ID'
         ];
     }

}
