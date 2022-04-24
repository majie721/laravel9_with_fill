<?php

namespace Tests;

use App\Enums\Common\RouteSymbol;
use App\Helpers\App;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

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
}
