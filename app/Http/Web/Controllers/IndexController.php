<?php
namespace App\Http\Web\Controllers;

use App\Beans\Bean;
use App\Http\Web\Beans\Demo\Demo;
use LaravelNemo\AttributeClass\ApiDoc;
use LaravelNemo\Nemo;


class IndexController
{

    #[ApiDoc('订单','demo',Bean::class,'测试接口')]
    public function demo(Bean $bean){
        var_dump($bean);
    }





}
