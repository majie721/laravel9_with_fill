<?php

namespace App\Console\Tools;


use App\Helpers\Str;
use GuzzleHttp\Handler\Proxy;
use Majie\Fills\Fill\Library\Functions\Func;
use Majie\Fills\Fill\PropertyInfo;
use Majie\Fills\Test\TestClass\Order;

class MarkdownGenerator implements GeneratorInterface
{

    private string $content;

    /**
     * @param  array<key=>ControllerDoc[]> $docs
     * @param string $title
     */
    public function __construct(protected array $docs,protected string $title){

    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    private function getMdHeader(){
        return [
            '[TOC]',
            '# ' . $this->title,
            '',
        ];
    }


    public function generate():FileStore{
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

                $bodyParam = $doc->requestBody();
                $lines[] = "#### {$apiIndex}.4 TypeScript 请求结构";
                $lines[] = "```";
                $lines[] = empty($bodyParam)?'{}':$this->getTsTypeDefine($bodyParam);
                $lines[] = "```";


                $lines[] = "#### {$apiIndex}.5 TypeScript 响应结构";
                $lines[] = "```";
                $lines[] = empty($doc->response)?'{}':$this->getTsTypeDefine($doc->response);
                $lines[] = "```";


                $lines[] = "#### {$apiIndex}.6 TypeScript 请求示例";
                $lines[] = "```json";
                $lines[] = empty($bodyParam)?'{}':$this->getRequestJson($bodyParam);
                $lines[] = "```";

                $lines[] = "#### {$apiIndex}.7 TypeScript 响应示例";
                $lines[] = "```json";
                $lines[] = empty($doc->response)?'{}':$this->getResponseJson($doc->response);
                $lines[] = "```";
            }

        }

        $content = implode(PHP_EOL, $lines);
        $this->content = $content;
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
    private function getTsTypeDefine(ParameterParser|ResponseParser $param){


        if(!$param->className){
            return '';
        }

        $items[] = $this->getTsType($param);

        if($param->child){
            foreach ($param->child as $item){
                $childObjectInterface =$this->getTsTypeDefine($item);
                $childObjectInterface && $items[] =  $childObjectInterface;
            }
        }

        return  implode(PHP_EOL,$items);
    }

    private function getTsType(ParameterParser|ResponseParser $param):string{ //todo ts undefind 和 null

        $namespace = $param->className;
        $interfaceName = self::toTsInterfaceName($param->className);
        $content[] ="{$namespace} :";
        $content[]= "interface {$interfaceName} {";
        $tab = "  ";
        foreach ($param->child as $prop){
            if($prop->className){
                if($prop->type === 'object'){
                    $this->getTsTypeDefine($param);

                    $doc = "{$tab}/** {$prop->document}  */" ;
                    $name = Str::uncamelize($prop->name);
                    $type = self::toTsInterfaceName($prop->className);

                    $content[] =  $doc;
                    $content[]= "{$tab}{$name}: {$type};";
                }

                if($prop->type ==='object[]'){
                    $this->getTsTypeDefine($prop);
                    $doc = "{$tab}/** {$prop->document}  */" ;
                    $name = Str::uncamelize($prop->name);
                    $type = self::toTsInterfaceName($prop->className).'[]';
                    $content[] =  $doc;
                    $content[] = "{$tab}{$name}: {$type};";
                }
            }

            if($prop->isBuiltin){
                $enumStr = $prop->isEnum ? "。枚举【".$prop->getEnumDesc()."】":'';
                $doc = "{$tab}/** {$prop->document}{$enumStr} */";
                $name = Str::uncamelize($prop->name);
                $type = self::transformTsType($prop->type);
                $content[] =  $doc;
                $content[]= "{$tab}{$name}: {$type};";
            }
        }
        $content[] = '}';
        return implode(PHP_EOL,$content);
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

    private function getRequestJson(ParameterParser $parser){
        $mock = [];
        $this->getJson($parser->className,$mock);
        return json_encode($mock,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }

    private function getJson(string $className,array &$mock){
        if($className){
            if(is_callable([$className,'getPropertiesInfo'])){
                $properties =  call_user_func([$className,'getPropertiesInfo']);
                /**
                 * @var  $key
                 * @var  $info PropertyInfo
                 */
                foreach ($properties as $keyName=>$info){
                    if(Func::isScalar($info->typeName)){
                        $mock[$keyName] = $info->doc?:'';
                    }
                    if('array'===$info->typeName){
                        if(Func::isScalar($info->arrayType)){
                            $mock[$keyName] = $info->doc?[$info->doc]:[];
                        }else{
                            $mock[$keyName] = [];
                            $mock[$keyName] = [$this->getJson($info->arrayType,$mock[$keyName])];
                        }

                    }
                    if(Func::isClass($info->typeName)){
                        $mock[$keyName] = [];
                        $mock[$keyName] = $this->getJson($info->className,$mock[$keyName]);
                    }
                }
                return $mock;
            }
            return [];
        }
    }

    private function getResponseJson(ResponseParser $parser){
        $mock = [];
        $this->getJson($parser->className,$mock);

        $response = [
            'code'=>0,
            'message'=>'message',
            'debug'=>[],
            'data'=>$mock,
            'timestamp'=>'timestamp',
        ];
        return json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }





}
