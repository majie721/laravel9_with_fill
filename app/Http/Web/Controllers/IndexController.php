<?php
namespace App\Http\Web\Controllers;

use App\Http\Web\Beans\Demo\Demo;
use http\Client\Curl\User;
use Majie\Fills\Fill\AttributeClass\ApiDoc;

class IndexController
{
    /**
     * @param $a
     * @param \App\Models\User $b
     * @param $c
     * @param int $d
     * @param float $e
     * @return void
     */
    #[ApiDoc('个人中心','用户信息',Demo::class,2)]
    public function user(  array $a,  $d=9,float $e){
        var_dump($a,$d,$e);die();

       $a = [
           'id'=>1,
           'code'=>'201222001',
           'created_at'=>'2022-4-25 14:32:37',
           'status'=>1,
           'product'=>[
               [
                    'id'=>78,
                    'name'=>'apple',
                    'price'=>19.6,
                    'attrs'=>['color','category']
               ]
           ]
       ];
       echo json_encode($a);die();
    }

    #[ApiDoc('个人中心','用户信息',Demo::class,1)]
    public function demo(Demo $bean){
        var_dump($bean);
    }
}
