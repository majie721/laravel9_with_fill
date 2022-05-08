<?php

use App\Helpers\App;
use App\Helpers\Router;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware([])->group(function (){
    $config =  config('nemo.route.web',[]);
    App::setRouteSymbol($config['name']);

    Route::any('{controller}/{action}', static function ($controller, $action)use ($config){

        return \LaravelNemo\Library\Router::dispatchRoute($controller,$action,$config);
    })->where('controller','.*');
});
