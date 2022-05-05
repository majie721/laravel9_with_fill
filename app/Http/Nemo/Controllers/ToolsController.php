<?php

namespace App\Http\Nemo\Controllers;

use App\Http\Nemo\Controllers\Beans\ClassBean;
use App\Http\Nemo\Controllers\Beans\JsonModelReq;
use App\Http\Nemo\Service\GenerateService;
use LaravelNemo\Doc\BeanGenerator;

class ToolsController
{

    /** @var string[][][]  */
    public array $a;

    public function __construct(public GenerateService $service)
    {
    }

    public function jsonModel(JsonModelReq $req){

        $rootNode = $req->list[0]??[];
        $files = [];
        if($rootNode && count($rootNode->children)){
            $classList =   $this->service->genJsonModel($rootNode->children,$req->namespace,$req->className);
            /** @var ClassBean $item */
            foreach ($classList as $item){
                $path = storage_path($req->className.DIRECTORY_SEPARATOR.$item->className);
                (new BeanGenerator($item))->generate()->store($path,true);
                $files[] = $path;
            }
        }

        dd($files);
    }

}
