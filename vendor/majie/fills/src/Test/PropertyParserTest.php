<?php

namespace Majie\Fills\Test;

use Majie\Fills\Fill\PropertyParser;
use Majie\Fills\Test\TestClass\BaseTest;
use Majie\Fills\Test\TestClass\Order;
use PHPUnit\Framework\TestCase;

Class PropertyParserTest extends BaseTest{

    public function testGetProxyPropertyData(){
        $order = new Order(['desc'=>"ssaaa12312312000"]);

        $PropertyParser = new PropertyParser($order);

       var_dump($order);
        $this->assertTrue(true);
    }


    public function testProxyPropertyDataDecorators(){
        $order = new Order(['desc'=>'aaabbbbccc999900000']);


       var_dump($order);

        $this->assertTrue(true);
    }


    public function testGetPropertiesInfo(){
        print_r(Order::getPropertiesInfo());
        $this->assertTrue(true);
    }

    public function testgetProperties(){
        print_r(Order::getProperties());
        $this->assertTrue(true);
    }


    public function testFillOrder(){
        $orderData = [
            'id'=>1,
            'order_code'=>'EL2022042001',
            'is_return'=>false,
            'service'=>['T','B'],
            'product'=>[
                [
                    'id'=>1,
                    'sku'=>'apple_01',
                    'title'=>'苹果1',
                    'num'=>1,
                    'created_at'=>'2022-4-15 14:55:34'
                ],
                [
                    'id'=>2,
                    'sku'=>'apple_02',
                    'title'=>'苹果2',
                    'num'=>2,
                    'created_at'=>'2022-4-18 14:55:34'
                ],
                [
                    'id'=>3,
                    'sku'=>'apple_03',
                    'title'=>'苹果3',
                    'num'=>3,
                    'created_at'=>'2022-4-21 14:55:34'
                ]
            ],
            'status'=>2,
            'status_text'=>'未知',
            'address'=>[
                'firstName'=>'jie',
                'lastName'=>'ma',
                'company'=>'哇哈哈',
                'address_1'=>'龙岗贝尔路',
            ],
            'desc'=>'ssaaa12312312000',
            'num'=>2
        ];

        $orderObj = new Order($orderData);
        var_dump($orderObj);

        //$this->assertTrue(true);
    }


}