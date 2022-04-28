<?php
namespace App\Http\Web\Controllers;

use App\Http\Web\Beans\Demo\Demo;
use http\Client\Curl\User;
use Majie\Fills\Fill\AttributeClass\ApiDoc;
use Majie\Fills\Fill\AttributeClass\Doc;

class IndexController
{
    #[ApiDoc('个人中心2','用户信息2',Demo::class,'','GET',3)]
    public function demo2(Demo $bean, #[Doc('ID')] int $ids=222){
        var_dump($bean);
    }

    /**
     * @param $a
     * @param \App\Models\User $b
     * @param $c
     * @param int $d
     * @param float $e
     * @return void
     */
    #[ApiDoc('个人中心2','用户信息',Demo::class,'','PUT',2)]
    public function user(  bool $a, int $d=9,float $e){
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

    #[ApiDoc('个人中心2','用户信息',Demo::class,'','GET',1)]
    public function demo(Demo $bean){
        var_dump($bean);
    }



}
