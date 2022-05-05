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

    /** @var string eg:App\Http\Nemo\Controllers\Beans\ServerOrder; */
    public string $class = '';
    /** @var string ServerOrder */
    public string $className = '';


    public static function jsonGen(JsonNode $node,string $namespace){
        $instance = PropertyInfo::fromItem([]);
        $instance->name = $node->name;
        $instance->type = $node->type;
        $instance->arrayType = $node->array_type?:'';
        $instance->desc = $node->desc?:'';
        $instance->mock = $node->mock?:"";
        $instance->class = self::getJsonType($node, $namespace);
        $instance->className = $instance->class?self::getClassName($node):'';
        return $instance;
    }

    private static function getJsonType(JsonNode $node,string $namespace){
        $classType = '';
        if($node->type==='object' || $node->array_type==='object'){
            $classType = self::getClassName($node);
            $classType = "{$namespace}\\{$classType}";
        }

        return $classType;
    }

    private static function getClassName(JsonNode $node){
        $path = substr($node->key,5);
        $pathArr = explode(".",$path);
        return implode(array_map('ucfirst',$pathArr));
    }
}
