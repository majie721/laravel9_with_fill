<?php

namespace Tests;

use App\Enums\Common\RouteSymbol;
use App\Helpers\App;
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
}
