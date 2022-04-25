<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Beans\Demo\Demo;
use http\Client\Curl\User;

class IndexController
{
    public function user(  $a, \App\Models\User $b, $c,int $d,float $e){
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

    public function demo(Demo $bean){
        var_dump($bean);
    }
}
