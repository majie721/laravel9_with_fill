<?php

namespace Majie\Fills\Test;

use Majie\Fills\Test\TestClass\BaseTest;
use Majie\Fills\Test\TestClass\Order;
use PHPUnit\Framework\TestCase;

Class CommonTest extends BaseTest {


    public function testA(){
        var_dump(1);
    }


    public function testAssignNull(){
        $order = new Order();
        $order->id = null;

        var_dump($order);
    }


    public function testAssignErrorArrayType(){
        $order = new Order();
        $order->product = null;

        var_dump($order);
    }


    public function testCallable(){
        is_callable();
    }
}