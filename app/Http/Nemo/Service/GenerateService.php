<?php

namespace App\Http\Nemo\Service;

use App\Http\Nemo\Controllers\Beans\ClassBean;
use App\Http\Nemo\Controllers\Beans\JsonModelReq;
use App\Http\Nemo\Controllers\Beans\JsonNode;
use App\Http\Nemo\Controllers\Beans\PropertyInfo;

class GenerateService
{

    /**
     * @param  $nodes JsonNode[]
     * @return array
     */
    public function genJsonModel(array $nodes,string $namespace,string $className){
        $classList[] = $this->parseJsonChildren($nodes,$namespace,$className);

        foreach ($nodes as $node){
            if($node->children){
                $classList[] = $this->genJsonModel($node->children,$namespace,ucfirst($node->name));
            }
        }

        return $classList;
    }

    public function parseJsonChildren(array $nodes,string $namespace,string $className){
        $classInfo = ClassBean::fromItem(['className'=>$className,'namespace'=>$namespace,'propertyList'=>[]]);
        foreach ($nodes as $node){
            $classInfo->propertyList[] = PropertyInfo::jsonGen($node,$namespace);
        }
        return $classInfo;
    }
}
