<?php

namespace App\Helpers;

use App\Enums\Common\RouteSymbol;

class App
{

    /**
     * 设置网站路由标记
     * @param string $symbol
     * @return void
     */
    public static function setRouteSymbol(string $symbol): void
    {
        !defined('APP_ROUTE_SYMBOL') && define('APP_ROUTE_SYMBOL',$symbol);
    }


    /**
     * 设置网站路由标记
     * @return string
     */
    public static function getRouteSymbol(): string
    {
      return  defined('APP_ROUTE_SYMBOL')?APP_ROUTE_SYMBOL:'';
    }

    /**
     * 是否是调试模式
     *
     * @return bool
     */
    public static function isDebug(): bool
    {
        return (bool)config('app.debug', false);
    }

}
