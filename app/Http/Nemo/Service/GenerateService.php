<?php

namespace App\Http\Nemo\Service;

use App\Http\Nemo\Controllers\Beans\ClassBean;
use App\Http\Nemo\Controllers\Beans\JsonModelReq;
use App\Http\Nemo\Controllers\Beans\JsonNode;
use App\Http\Nemo\Controllers\Beans\PropertyInfo;
use LaravelNemo\Doc\BeanGenerator;
use LaravelNemo\Library\Utils;

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
                $classList = [...$classList,...$this->genJsonModel($node->children,$namespace,Utils::camelize($node->name))];
            }
        }

        return $classList;
    }

    /**
     * @param JsonNode[] $nodes
     * @param string $namespace
     * @param string $className
     * @return ClassBean
     */
    public function parseJsonChildren(array $nodes,string $namespace,string $className){
        $classInfo = ClassBean::fromItem(['className'=>$className,'namespace'=>$namespace,'propertyList'=>[]]);
        foreach ($nodes as $node){
            $classInfo->propertyList[] = PropertyInfo::jsonGen($node,$namespace);
        }
        return $classInfo;
    }

    /**
     * @param JsonNode $node
     * @return bool
     */
    public function canSkip($node){
        if(preg_match('/^(\[\]|int|bool|float|string|array).*?/',(string)$node->array_type)){
            return true;
        }else{
            return false;
        }
    }
}
