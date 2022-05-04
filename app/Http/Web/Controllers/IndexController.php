<?php
namespace App\Http\Web\Controllers;

use App\Http\Web\Beans\Demo\Demo;
use LaravelNemo\Nemo;


class IndexController
{

    public function demo(){
        var_dump(Nemo::class);
    }





}
