<?php

namespace App\Helpers;

use App\Exceptions\HttpForbiddenException;
use App\Exceptions\HttpNotFoundException;
use App\Exceptions\ParamsException;
use App\Helpers\Attributes\ArrayShapeConst;
use JetBrains\PhpStorm\ArrayShape;

class Router
{
    /**
     * @param string $controller
     * @param string $action
     * @param array $config
     * @return mixed
     * @throws HttpForbiddenException
     * @throws ParamsException
     */
    public static function dispatchRoute(string $controllerPath, string $action,#[ArrayShape(ArrayShapeConst::ROUTE_CONFIG)]array$config):mixed{
        $controllerArr = explode('/',$controllerPath);
        foreach ($controllerArr as &$item){
            $item = Str::camelize($item,$config['separator']??'_');
        }

        $controller = implode("\\",$controllerArr);
        $controller = "{$config['namespace']}\\{$controller}Controller"; //eg. "App\Http\Web\Controllers\IndexController"

        $action = lcfirst(Str::camelize($action));//action为小驼峰
        if(in_array($action,config('controller.forbidden_actions'),true)){
            throw new HttpForbiddenException('The method can not access.');
        }

        if(!class_exists($controller) || !method_exists($controller,$action)){
            throw new HttpForbiddenException('The method not found.');
        }


        $method = new \ReflectionMethod($controller, $action);
        if($method->isStatic() || !$method->isPublic()){
            throw new HttpForbiddenException('The method can not access.');
        }

        $args = [];
        $parameters = $method->getParameters();

        foreach ($parameters as $parameter){
            $paramName = $parameter->getName();
            $type =  $parameter->getType()?->getName();
            $args[] =  match ($type){
                 null =>throw new ParamsException("The type of parameter {$paramName} is undefined." ),
                'int','string','bool','float'=>[], //todo
            };

            var_dump($paramName,$type);
        }


        die();
        dd($controllerPath,$action,$config);
    }

}
