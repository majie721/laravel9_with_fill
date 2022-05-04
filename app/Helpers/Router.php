<?php

namespace App\Helpers;

use App\Exceptions\HttpForbiddenException;
use App\Exceptions\HttpNotFoundException;
use App\Exceptions\ParamsException;
use App\Helpers\Attributes\ArrayShapeConst;
use JetBrains\PhpStorm\ArrayShape;
use LaravelNemo\Nemo;
use Majie\Fills\Fill\Proxy;

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
    public static function dispatchRoute(string $controllerPath, string $action, #[ArrayShape(ArrayShapeConst::ROUTE_CONFIG)] array $config): mixed
    {
        $controllerArr = explode('/', $controllerPath);
        foreach ($controllerArr as &$item) {
            $item = Str::camelize($item, $config['separator'] ?? '_');
        }

        $controller = implode("\\", $controllerArr);
        $controller = "{$config['namespace']}\\{$controller}Controller"; //eg. "App\Http\Web\Controllers\ToolsController"

        $action = lcfirst(Str::camelize($action));//action为小驼峰
        if (in_array($action, config('controller.forbidden_actions'), true)) {
            throw new HttpForbiddenException('The method can not access.');
        }

        if (!class_exists($controller) || !method_exists($controller, $action)) {
            throw new HttpForbiddenException('The method not found.');
        }


        $method = new \ReflectionMethod($controller, $action);
        if ($method->isStatic() || !$method->isPublic()) {
            throw new HttpForbiddenException('The method can not access.');
        }

        $args = [];
        $parameters = $method->getParameters();

        foreach ($parameters as $parameter) {
            $paramName = $parameter->getName();
            $type = $parameter->getType()?->getName();

            $args[] = match (true) {
                class_exists($type) => self::matchTypeIsClass($parameter,$type),
                in_array($type, ['int', 'string', 'bool', 'float', 'array'], true) => self::matchTypeIsNormal($parameter, $paramName, $type),
                $type === null => self::matchTypeIsNll($parameter, $paramName),
                default => throw new ParamsException("The type of parameter ({$paramName}) is exception.")
            };
        }

        $class = app($controller);

        return $class->{$action}(...$args);
    }


    private static function matchTypeIsNormal(\ReflectionParameter $parameter, string $paramName, string $type)
    {
        $requestData = \request()->all();
        if (isset($requestData[Str::uncamelize($paramName)])) { //约定为蛇形前端参数
            self::checkParamType($type, $paramName, $requestData[Str::uncamelize($paramName)]);
            return $requestData[Str::uncamelize($paramName)];
        } elseif ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        } elseif ($parameter->allowsNull()) {
            return null;
        } else {
            throw new ParamsException("The value of parameter ({$paramName}) is required.");
        }
    }


    /**
     * @param \ReflectionParameter $parameter
     * @param string $paramName
     * @return \Illuminate\Contracts\Foundation\Application|Proxy|mixed
     */
    private static function matchTypeIsClass(\ReflectionParameter $parameter,string $className)
    {
        $resolvedClass = app($className);
        if ($resolvedClass instanceof Nemo) {
            $resolvedClass =  $resolvedClass::fromItem(\request()->all());
        }

        return $resolvedClass;
    }

    /**
     * @param \ReflectionParameter $parameter
     * @param string $paramName
     * @return mixed|null
     * @throws ParamsException
     */
    private static function matchTypeIsNll(\ReflectionParameter $parameter, string $paramName)
    {
        return self::matchTypeIsNormal($parameter, $paramName, 'null');
    }

    /**
     * 检查参数类型
     *
     * @param string $typeName
     * @param string $paramName
     * @param $value
     * @throws BusinessException
     */
    protected static function checkParamType(string $typeName, string $paramName, &$value): void
    {

        switch ($typeName) {
            case 'string':
                if (!is_string($value) &&
                    !is_int($value) &&
                    !is_float($value)
                ) {
                    throw new ParamsException("The type of parameter ({$paramName}) must be string data.");
                }
                $value = (string)$value;
                break;
            case 'array':
                if (!is_array($value)) {
                    throw new ParamsException("The type of parameter ({$paramName}) must be array.");
                }
                break;
            case 'int':
                if (!preg_match('/^-?[1-9]?\d*$/', $value)) {
                    throw new ParamsException("The type of parameter ({$paramName}) must be integer.");
                }
                $value = (int)$value;
                break;
            case 'bool':
                if (!is_bool($value)) {
                    throw new ParamsException("The type of parameter ({$paramName}) must be bool.");
                }
                break;
            case 'float':
                if (!Common::isTrueFloat($value)) {
                    throw new ParamsException("The type of parameter ({$paramName}) must be float.");
                }
                $value = (float)$value;
                break;
        }
    }

}
