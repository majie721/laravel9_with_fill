<?php

namespace App\Http\Nemo\Controllers\Beans;

use LaravelNemo\Nemo;

class PropertyInfo extends Nemo
{
    public string $name;

    public string $type;

    public string $arrayType;

    public string $desc;

    public string $mock;

    public string $className = '';


    public static function jsonGen(JsonNode $node,string $namespace){
        $instance = PropertyInfo::fromItem([]);
        $instance->name = $node->name;
        $instance->type = $node->type;
        $instance->arrayType = $node->array_type?:'';
        $instance->desc = $node->desc?:'';
        $instance->mock = $node->mock?:"";
        $instance->className = self::getJsonType($node, $namespace);
        return $instance;
    }

    private static function getJsonType(JsonNode $node,string $namespace){
        $classType = '';
        if($node->type==='object'){
            $path = substr($node->key,5);
            $pathArr = explode(",",$path);
            $classType = implode(array_map('ucfirst',$pathArr));
            $classType = "{$namespace}\\{$classType}";
        }

        return $classType;
    }
}
