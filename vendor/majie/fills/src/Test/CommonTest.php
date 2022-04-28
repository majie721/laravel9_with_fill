<?php

namespace Majie\Fills\Test;

use App\Http\Web\Beans\Demo\Demo;
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

    public function testNullCall(){
        var_dump(Demo::class);
        $docData = null;
        var_dump($docData->getDoc());
    }
}
