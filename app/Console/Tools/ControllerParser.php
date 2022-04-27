<?php

namespace App\Console\Tools;

use App\Helpers\App;
use App\Helpers\Str;
use Illuminate\Support\Facades\File;
use Majie\Fills\Fill\AttributeClass\ApiDoc;
use Majie\Fills\Fill\AttributeClass\Doc;


class ControllerParser
{

    private string $className;

    private array $documents = [];

    /**
     * @param string $filePath 文件路径
     * @param string $separator api分割符号
     * @throws \Exception
     */
    public function __construct(public string  $filePath,
                                private string $separator)
    {
        if (!file_exists($filePath)) {
            throw new \Exception('文件路径不存在');
        }


    }


    public function init()
    {
        $this->getClass($this->filePath);
        return $this;
    }

    /**
     * 根据 文件内容解析出类名
     * @param $filePath
     * @return $this
     * @throws \Exception
     */
    public function getClass($filePath)
    {
        $content = file_get_contents($filePath);
        preg_match("/^<\?php\s+namespace(.*?);[\s\S]+class\s+(.*?Controller)[\s\S]*/", $content, $match);
        $className = trim($match[1]) . '\\' . trim($match[2]);
        if (!class_exists($className)) {
            throw new \Exception("{$filePath}文件解析失败");
        }
        $this->className = $className;
        return $this;
    }


    public function parser()
    {
        $reflectClass = new \ReflectionClass($this->className);
        $methods = $reflectClass->getMethods();

        $uriPath = $this->getUri($this->className, $this->separator);

        if (!$uriPath) {
            return [];
        }

        foreach ($methods as $method) {
            if (!$method->isPublic()) { //非公有方法不解析
                continue;
            }

            $apiDocAttribute = $method->getAttributes(ApiDoc::class);//没有ApiDoc注解的不解析
            if (empty($apiDocAttribute)) {
                continue;
            }

            $methodName = $method->getName();
            $attributeData = $apiDocAttribute[0]->newInstance();
            $params = $method->getParameters();
            $paramData = [];
            foreach ($params as $param) {
                try {
                    $paramData[] = $this->parseParam($param);
                } catch (\Throwable $e) {
                    throw new \Exception("Controller{$this->className}下的{$methodName}方法解析失败:{$e->getMessage()}");
                }
            }

            $methodName = Str::uncamelize($methodName);
            $document = new ControllerDoc();
            $document->name         = $methodName;
            $document->module       = $attributeData->module;
            $document->title        = $attributeData->name;
            $document->response     = $attributeData->response;
            $document->method       = $attributeData->method;
            $document->sort         = $attributeData->sort;
            $document->desc         = $attributeData->desc;
            $document->uri          = "{$uriPath}/$methodName";
            $document->requestParam = $paramData;
            $this->documents[] = $document;
        }

        return $this->documents;
    }


    /**
     * 获取路径
     * @param string $class
     * @param string $separator
     * @return string|void
     */
    private function getUri(string $class, string $separator = "/")
    {
        preg_match("/.*?Controllers(\S*)\\\(\w+)Controller/", $class, $match);
        if ($match) {
            $path = trim("{$match[1]}\\{$match[2]}", '\\');

            $arr = array_map([Str::class, 'uncamelize'], explode('\\', $path));
            return implode($separator, $arr);
        }

        return '';
    }

    /**
     * 解析参数
     * @param \ReflectionParameter $parameter
     * @return ParameterParser
     * @throws \JsonException
     * @throws \Majie\Fills\Fill\Exceptions\DocumentPropertyError
     * @throws \ReflectionException
     */
    private function parseParam(\ReflectionParameter $parameter)
    {
        $parameterParser = new ParameterParser($parameter);
        return $parameterParser->parse($parameter, 1);
    }


}
