<?php

namespace Majie\Fills\Test\TestClass;

class Str
{
    public static function endFlg($val,$endFlg ="..."){
        return $val.$endFlg;
    }


    public function startFlg($val,$endFlg ="sss"){
        return $endFlg.$val;
    }



}