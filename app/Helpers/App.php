<?php

namespace App\Helpers;

use App\Enums\Common\RouteSymbol;

class App
{

    /**
     * 设置网站路由标记
     * @param RouteSymbol $symbol
     * @return void
     */
    public static function setRouteSymbol(RouteSymbol $symbol)
    {
        !defined('APP_ROUTE_SYMBOL') && define('APP_ROUTE_SYMBOL',$symbol->value);
    }


    /**
     * 设置网站路由标记
     * @return RouteSymbol|null
     */
    public static function getRouteSymbol(): ?RouteSymbol
    {
      return  RouteSymbol::tryFrom(APP_ROUTE_SYMBOL);
    }

}
