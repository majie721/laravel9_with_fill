<?php

namespace App\Console\Tools;


use App\Helpers\Str;

class MarkdownGenerator
{


    /**
     * @param  array<key=>ControllerDoc[]> $docs
     * @param string $title
     */
    public function __construct(protected array $docs,protected string $title){

    }

    private function getMdHeader(){
        return [
            '[TOC]',
            '# ' . $this->title,
            '',
        ];
    }


    public function generate(){
        $lines  = $this->getMdHeader();

        $subjectIndex = 0;
        foreach ($this->docs as $module=>$docArr){
            $subjectIndex++;
            $lines[] = "## {$subjectIndex} {$module}";
            /** @var ControllerDoc $doc */
            $index = 0;
            foreach ($docArr as $doc){
                $index++;
                $apiIndex = "{$subjectIndex}.{$index}";
                $lines[] = "### {$apiIndex} {$doc->title}";
                $lines[] = "- **接口说明：** {$doc->desc}";
                $lines[] = "- **接口地址：** {$doc->uri}";
                $lines[] = "- **请求方式：** {$doc->method}";

                $lines[] = "#### {$apiIndex}.1 Query参数";
                $lines[] = "| 参数名称 | 类型 | 是否必填 | 默认值 | 描述 |";
                $lines[] = "| --- | --- | --- | --- | --- |";
                $paramLines =  $this->getQueryParamLine($doc->requestParam);
                $lines = [...$lines,...$paramLines];

                $lines[] = "#### {$apiIndex}.2 Request Body";
                $lines[] = "| 参数名称 | 类型 | 是否必填 | 默认值 | 描述 |";
                $lines[] = "| --- | --- | --- | --- | --- |";
                $paramLines =  $this->getRequestBodyLine($doc->requestParam);
                $lines = [...$lines,...$paramLines];

                $lines[] = "#### {$apiIndex}.3 Response Body";
                $lines[] = "| 参数名称 | 类型 | 是否必填 | 默认值 | 描述 |";
                $lines[] = "| --- | --- | --- | --- | --- |";
                $paramLines =  $this->getResponseBodyLine([$doc->response]);
                $lines = [...$lines,...$paramLines];

                $bodyParam = array_filter($doc->requestParam,function ($val){
                    return !$val->isQueryParam;
                });
                $lines[] = "#### {$apiIndex}.4 TypeScript 请求结构";
                $lines[] = "```json";
                $lines[] = empty($bodyParam)?'{}':$this->getTsTypeDefine($bodyParam);
                $lines[] = "```";


            }

        }

        $content = implode(PHP_EOL, $lines);
        return new FileStore($content, 'md');
    }


    /**
     * @param ParameterParser[]|ResponseParser[] $paramsList
     * @param string|null $filterType queryParam,requestBody
     * @return array
     */
    public function getLine(array $paramsList,?string $filterType=null):array{
        $elements = [];
        /** @var ParameterParser $item */
        foreach ($paramsList as $item){
            if(!$item){
                continue;
            }

            if(($filterType === 'queryParam') && !$item->isQueryParam){
                continue;
            }

            if(($filterType === 'requestBody') && $item->isQueryParam){
               continue;
            }
            $name = self::placeholder($item->depth-1).$item->name;
            $type = $item->type;
            $required = $item->isRequired ? 'Yes':'No';
            $default = $item->hasDefaultValue?$item->defaultValue:'';
            $desc = $item->document;
            if($item->isEnum){
                $enumData = json_encode(json_decode($item->enumData),JSON_UNESCAPED_UNICODE);
                $desc .= "(枚举：【{$enumData}】)";
            }
            $item->depth >0 && $elements[] = "| $name | $type | $required | $default | $desc |";
            if($item->child){
                $elements = [...$elements,...$this->getLine($item->child,$filterType)];
            }
       }
        return  $elements;
    }

    /**
     * 请求参数内容
     * @param array $paramsList
     * @return array
     */
    public function getQueryParamLine(array $paramsList):array{
        return $this->getLine($paramsList,'queryParam');
    }

    /**
     * 请求body内容
     * @param array $paramsList
     * @return array
     */
    public function getRequestBodyLine(array $paramsList):array{
        return $this->getLine($paramsList,'requestBody');
    }

    /**
     * 请求响应body内容
     * @param array $paramsList
     * @return array
     */
    public function getResponseBodyLine(array $paramsList):array{
        return $this->getLine($paramsList);
    }

    /**
     * 占位符
     * @param int $n
     * @return string
     */
    private static function placeholder(int $n){
        if($n>0){
            return self::tab($n)."--";
        }
        return '';
    }

    private static function tab($n){
       return str_repeat("&nbsp;",$n*2);
    }

    /**
     * 请求body ts 的结构定义
     * @param ParameterParser $param
     * @return void
     *
     *
     */
    private function getTsTypeDefine(ParameterParser $param){
        $interfaceList = [];
        if(!$param->className){
            return '';
        }
        $namespace = $param->className;
        $interfaceName = self::toTsInterfaceName($param->className);
        if($param->child){
            $items = [];
            foreach ($param->child as $item){
               $items[] =  $this->getTsType($item);
            }

            $content = implode(PHP_EOL,$items);

        }



    }

    private function getTsType(ParameterParser $param){ //todo ts undefind 和 null
        if($param->className){
            if($param->type === 'object'){
                $this->getTsTypeDefine($param);

                $doc = "/** {$param->document}  */" ;
                $name = Str::uncamelize($param->name);
                $type = self::toTsInterfaceName($param->className);

                $content =  $doc.PHP_EOL;
                $content .= " {$name}: {$type}";
                return $content;
            }

            if($param->type ==='object[]'){
                $this->getTsTypeDefine($param);
                $doc = "/** {$param->document}  */" ;
                $name = Str::uncamelize($param->name);
                $type = self::toTsInterfaceName($param->className).'[]';
                $content =  $doc.PHP_EOL;
                $content .= " {$name}: {$type}";
                return $content;
            }
        }

        if($param->isBuiltin){
            $enumStr = $param->isEnum ? "。枚举【".$param->getEnumDesc()."】":'';
            $doc = $param->document.$enumStr;
            $name = Str::uncamelize($param->name);
            $type = self::transformTsType($param->type);
            $content =  $doc.PHP_EOL;
            $content .= " {$name}: {$type}";
            return $content;
        }

    }

    /**
     * @param string $parameterParserType
     * @return string
     */
    private static function transformTsType(string $parameterParserType):string{
        return match ($parameterParserType){
            'string'=>'string',
            'float','int'=>'number',
            'bool'=>'boolean',
            'int[]','float[]'=>"number[]",
            'bool[]'=>"bool[]",
            'string[]'=>"string[]",
            '[]'=>'[],'
        };
    }

    /**
     * php的类名作为Ts的类名(App\Http\Web\Beans\Demo\Demo获取Demo)
     * @param string $phpClassName
     * @return string|null
     */
    private static function toTsInterfaceName(string $phpClassName){
        $arr=  explode("\\",$phpClassName);
       return  array_pop($arr);
    }


}
