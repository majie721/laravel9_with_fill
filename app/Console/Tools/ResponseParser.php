<?php

namespace App\Console\Tools;

use App\Helpers\Str;
use JetBrains\PhpStorm\ArrayShape;
use Majie\Fills\Fill\AttributeClass\Doc;
use Majie\Fills\Fill\AttributeClass\Enum;
use Majie\Fills\Fill\Exceptions\DocumentPropertyError;
use Majie\Fills\Fill\Library\Functions\Func;
use Majie\Fills\Fill\PropertyInfo;
use Majie\Fills\Fill\Proxy;

class ResponseParser
{

    /** @var string 参数名称(前端蛇形,php小驼峰) */
    public string $name;

    /** @var string 类型（scalar,array,object,object[],null;depth=0时响应仅支持，object 和 null） */
    public string $type;

    /** @var bool 是否标量类型: int,string,bool,float,int[],string[],bool[],float[] */
    public bool $isBuiltin;

    /** @var string 文档 */
    public string $document;

    /** @var bool 是否枚举 */
    public bool $isEnum;

    /** @var string 枚举值 */
    public string $enumData;

    /** @var array 子元素 */
    public array $child;

    /** @var int 元素层级深度 */
    public int $depth;

    /** @var bool 是否可能为空 */
    public bool $isRequired = false;

    /** @var bool  */
    public bool $hasDefaultValue = false;

    /** @var string 对象类名 */
    public string $className = '';

    public function __construct()
    {
    }


    /**
     * @param string|null $responseAttribute
     * @return ResponseParser|null
     * @throws DocumentPropertyError
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function parse(string|null $responseAttribute)
    {

        if(null === $responseAttribute){
           return null;
        }

        if(!class_exists($responseAttribute)){
            throw new \Exception("响应对象{$responseAttribute}不支持或者不存在");
        }


        $this->child = $this->parserClassType($responseAttribute, 1);

        $this->name = '';
        $this->depth = 0;
        $this->type = '';
        $this->document = '';
        $this->isBuiltin = false;
        $this->isEnum = false;
        $this->enumData = '';
        $this->isRequired = true;
        $this->hasDefaultValue = false;
        $this->className = $responseAttribute;

        return $this;
    }



    /**
     * @param string $className
     * @param int $depth
     * @return ParameterParser[]
     * @throws \JsonException
     * @throws DocumentPropertyError
     * @throws \ReflectionException
     */
    private function parserClassType(string $className, int $depth)
    {
        if (!class_exists($className)) {
            throw new \Exception("{$this->name}#{$className}不存在");
        }
        $properties = [];
        $instance = (new \ReflectionClass($className))->newInstance(null);
        if ($instance instanceof Proxy) {
            $data = $instance::getPropertiesInfo(true,true);
            if (empty($data)) {
                throw new \Exception("{$className}不能为空对象");
            }

            /**
             * @var  $key string
             * @var  $datum PropertyInfo
             */
            foreach ($data as $key => $datum) {
                if(!$datum->typeName){
                    throw new \Exception("{$className}的{$key}属性未定义");
                }

                if(Func::isScalar($datum->typeName)){
                    $properties[] = $this->newScalar($datum,$depth);
                    continue;
                }
                if('array' === $datum->typeName){
                    if($datum->isBuiltin){
                        $properties[] =  $this->newScalarArray($datum,$depth);
                        continue;
                    }else{ //对象数组
                        $instance = $this->newObjectArray($datum,$depth)->setClassName($datum->arrayType);
                        $instance->child =  $this->parserClassType($datum->arrayType,$depth+1);
                        $properties[] = $instance;
                        continue;
                    }
                }

                if(class_exists($datum->typeName)){ //对象
                    $instance = $this->newObject($datum,$depth)->setClassName($datum->typeName);
                    $instance->child =  $this->parserClassType($datum->typeName,$depth+1);
                    $properties[] =   $instance;
                    continue;
                }
                throw new \Exception("{$className}的{$key}属性异常");
            }

            return  $properties;
        }


        throw new \Exception("{$className} 需要继承Proxy实例");
    }


    /**
     * 填充标量类型
     * @param PropertyInfo $info
     * @param int $depth
     * @return ParameterParser
     * @throws \JsonException
     */
    public function newScalar(PropertyInfo $info,int $depth)
    {
        return $this->new( $info, $depth,'scalar');
    }

    /**
     * 填充标量数组类型(非数组对象)
     * @param PropertyInfo $info
     * @param int $depth
     * @return ParameterParser
     * @throws \JsonException
     */
    public function newScalarArray(PropertyInfo $info,int $depth)
    {
        return $this->new( $info, $depth,'array');
    }

    /**
     * 填充对象
     * @param PropertyInfo $info
     * @param int $depth
     * @return ParameterParser
     * @throws \JsonException
     */
    public function newObject(PropertyInfo $info,int $depth)
    {
        return $this->new( $info, $depth,'object');
    }

    /**
     * 填充对象数组类型
     * @param PropertyInfo $info
     * @param int $depth
     * @return ParameterParser
     * @throws \JsonException
     */
    public function newObjectArray(PropertyInfo $info,int $depth)
    {
        return $this->new( $info, $depth,'object[]');
    }


    /**
     * @param PropertyInfo $info
     * @param int $depth
     * @param string $type scalar,array,object,object[]
     * @return ParameterParser
     * @throws \JsonException
     */
    public function new(PropertyInfo $info,int $depth,string $type='scalar')
    {
        $instance = new self();
        $instance->name = $info->name;
        $instance->type = match ($type){
            'scalar'=>$info->typeName,
            'array'=>"{$info->arrayType}[]", //标量数组
            'object'=>'object',
            'object[]'=>$type,
        };
        $instance->document = $info->doc;
        $instance->isEnum = !empty($info->enumInfo);
        $instance->enumData = $instance->isEnum? json_encode($info->enumInfo, JSON_THROW_ON_ERROR) :'';
        $instance->child = [];
        $instance->depth = $depth;
        $instance->isBuiltin = in_array($type, ['scalar', 'array'], true);
        $instance->isRequired = $info->allowsNull===false;
        return $instance;
    }

    /**
     * @param string $className
     * @return $this
     */
    private function setClassName(string $className):self{
        $this->className = $className;
        return $this;
    }


    public function getEnumDesc():string{
        if(!$this->isEnum){
            return '';
        }

        $info = json_decode($this->enumData,true);
        $arr = [];
        foreach ($info['labelData'] as $datum){
            $arr[] =$datum['label'].":".$datum['value'];
        }
        return implode(',',$arr);
    }

}
