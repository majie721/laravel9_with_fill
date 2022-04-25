<?php

namespace Tests;

use App\Enums\Common\RouteSymbol;
use App\Helpers\App;
use App\Helpers\Common;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Log;

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
}
