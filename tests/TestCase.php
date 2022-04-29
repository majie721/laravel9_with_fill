<?php

namespace Tests;

use App\Console\Commands\GenerateDocument;
use App\Enums\Common\RouteSymbol;
use App\Helpers\App;
use App\Helpers\Common;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Majie\Fills\Test\TestClass\Order;
use Majie\Fills\Test\TestClass\OrderProduct;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    public function testRouteSymbol(){
        dd(RouteSymbol::values());
    }

    public function testRouteSymbolSet(){
        App::setRouteSymbol(RouteSymbol::API);
        dd(APP_ROUTE_SYMBOL);
    }

    public function testLoger(){
        Log::emergency("系统挂掉了");
        Log::alert("数据库访问异常");
        Log::critical("系统出现未知错误");
        Log::error("指定变量不存在");
        Log::warning("该方法已经被废弃");
        Log::notice("用户在异地登录");
        Log::info("用户xxx登录成功");
        Log::debug("调试信息");

    }

    public function testType(){
        $f = '1.0';
        var_dump(is_float($f));
    }

    public function testIsTrueFloat(){

        var_dump((int)(-1.9));
        var_dump(Common::isTrueFloat(-12));
       var_dump(Common::isTrueFloat(0));
       var_dump(Common::isTrueFloat(12.2));
       var_dump(Common::isTrueFloat(-12.2));
       var_dump(Common::isTrueFloat("0"));
       var_dump(Common::isTrueFloat("0.00"));
       var_dump(Common::isTrueFloat("0.02"));
       var_dump(Common::isTrueFloat(1e3));
       var_dump(Common::isTrueFloat(-8E+3));

       var_dump((float)"-8E+3");

    }


    public function testValidate(){
        $data = [
            'id'=>'aasd',
            'sku'=>'appal111111111e'
        ];

        $product = OrderProduct::fromItem($data);

    }

    public function testValidate2(){
        $data = [
            'id'=>'aasd',
            'sku'=>'appale'
        ];

        $rule = [
            'id'=>"integer|required|min:1|max:100",
            'sku'=>"string|required|min:1|max:10|starts_with:sku,MJ",
        ];

        $messages =  [
            'id.required' => ':ttribute is required',
            'id.integer' => ':attribute message must be int',
        ];

        $attributes =  [
            'id'=>'Product ID'
        ];

        $validater =  Validator::make($data,$rule,$messages,$attributes);
        if($validater->fails()){
            var_dump($validater->errors()->all());
            var_dump($validater->errors()->first());
        }

    }



    public function testsort(){
//        $data[] = array('volume' => 67, 'edition' => 2);
//        $data[] = array('volume' => 86, 'edition' => 1);
//        $data[] = array('volume' => 85, 'edition' => 6);
//        $data[] = array('volume' => 98, 'edition' => 2);
//        $data[] = array('volume' => 86, 'edition' => 6);
//        $data[] = array('volume' => 67, 'edition' => 7);
//
//        $res =  array_multisort (array_column($data, 'volume'), SORT_DESC, $data);
//        var_dump($res,$data);


        var_dump();
    }

    public function testparser(){
        $hander = new GenerateDocument();
        $hander->handle();
    }


}
