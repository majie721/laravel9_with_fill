<?php

namespace App\Http\Nemo\Controllers;

use App\Http\Nemo\Controllers\Beans\JsonModelReq;
use App\Http\Nemo\Service\GenerateService;

class ToolsController
{
    public function __construct(public GenerateService $service)
    {
    }

    public function jsonModel(JsonModelReq $req){

        $rootNode = $req->list[0]??[];
        if($rootNode && count($rootNode->children)){
          $res =   $this->service->genJsonModel($rootNode->children,$req->namespace,$req->className);
        }

        var_dump($res);die();

    }

}
