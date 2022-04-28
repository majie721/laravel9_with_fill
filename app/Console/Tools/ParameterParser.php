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

class ParameterParser
{

    /** @var string 参数名称(前端蛇形,php小驼峰) */
    public string $name;

    /** @var string 类型(int,string,bool,float,object,int[],string[],bool[],float[],object[]) */
    public string $type;

    /** @var bool 是否标量类型: int,string,bool,float,int[],string[],bool[],float[] */
    public bool $isBuiltin;

    /** @var bool 是否有默认值 */
    public bool $hasDefaultValue;

    /** @var string|null 默认值 */
    public string|null $defaultValue;

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

    /** @var bool 是否必填(默认所有参数必填,有option标记,默认值可为空的除外) */
    public bool $isRequired = true;

    /** @var bool 是否为查询参数 */
    public bool $isQueryParam = false;

    /** @var string 类名称 */
    public string $className = '';


    public function __construct()
    {
    }


    /**
     * @param \ReflectionParameter $parameter
     * @return $this
     * @throws DocumentPropertyError
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function parse(\ReflectionParameter $parameter)
    {
        $this->name = Str::uncamelize($parameter->getName());
        return $this->parseType($parameter);
    }


    /**
     * @param \ReflectionParameter $parameter
     * @return $this
     * @throws DocumentPropertyError
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function parseType(\ReflectionParameter $parameter)
    {
        $reflectionType = $parameter->getType();
        $cameName = Str::camelize($this->name);
        if (null === $reflectionType) {
            throw new \Exception("参数[{$cameName}]的类型不能为空");
        }

        if ($reflectionType instanceof \ReflectionNamedType) {
            $type = $reflectionType->getName();
            if (Func::isScalar($type)) {
                return $this->setParseTypeData($type, [], true, $parameter, true,1);
            } elseif ('array' === $type) {//数组可以根据ArrayShape注解解析
                $attributes = $parameter->getAttributes(ArrayShape::class);
                if (empty($attributes)) {
                    return $this->setParseTypeData('[]', [], true, $parameter,false,0);
                }

                $typeInArrayShape = $attributes[0]->getName();
                if (Func::isScalar($typeInArrayShape)) {
                    $type = "{$typeInArrayShape}[]";
                    return $this->setParseTypeData($type, [], true, $parameter,false,0);
                }
                $typeInArrayShape = $attributes[0]->getName();
                $type = "{$typeInArrayShape}[]";

                $child = $this->parserClassType($typeInArrayShape,  1);
                return $this->setParseTypeData($type, $child, false, $parameter,false,0)->setClassName($typeInArrayShape);
            } else { //对象
                $parsData = $this->setParseTypeData($type, [], false, $parameter,false,0)->setClassName($type);
                $parsData->child = $this->parserClassType($type, 1);
                return $parsData;
            }
        }

        throw new \Exception("参数[{$cameName}]的声明类型不支持");
    }


    /**
     *
     * @param string $type
     * @param array $child
     * @param bool $isBuiltin
     * @param \ReflectionParameter $parameter
     * @param bool $isQueryPara
     * @param int|null $depth
     * @return $this
     * @throws \JsonException
     * @throws \ReflectionException
     */
    private function setParseTypeData(string $type, array $child, bool $isBuiltin, \ReflectionParameter $parameter, bool $isQueryPara = false,int $depth=null):self
    {

        $docData = isset($parameter->getAttributes(Doc::class)[0]) ? $parameter->getAttributes(Doc::class)[0]->newInstance() : null;
        $this->child = $child;
        $this->isBuiltin = $isBuiltin;
        $this->type = $type;
        $this->hasDefaultValue = $parameter->isDefaultValueAvailable();
        $this->defaultValue = $this->hasDefaultValue ? $parameter->getDefaultValue() : null;
        $this->document = (string)$docData?->getDoc();
        $this->isEnum = !empty($parameter->getAttributes(Enum::class));
        $this->isQueryParam = $isQueryPara;
        $this->isRequired = !($this->hasDefaultValue || $parameter->allowsNull() || $docData?->getOption());
        $this->child = [];
        !is_null($depth) && $this->depth = $depth;
        $this->enumData = $this->isEnum ? json_encode($parameter->getAttributes(Enum::class)[0]->newInstance()->getEnumInfo(), JSON_THROW_ON_ERROR) : '';

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
            $cameName = Str::camelize($this->name);
            throw new \Exception("{$cameName}#{$className} 不存在");
        }
        $properties = [];
        $instance = (new \ReflectionClass($className))->newInstance(null);
        if ($instance instanceof Proxy) {
            $data = $instance::getPropertiesInfo(true, true);
            if (empty($data)) {
                throw new \Exception("{$className}不能为空对象");
            }

            /**
             * @var  $key string
             * @var  $datum PropertyInfo
             */
            foreach ($data as $key => $datum) {
                if (!$datum->typeName) {
                    throw new \Exception("{$className}的{$key}属性未定义");
                }

                if (Func::isScalar($datum->typeName)) {
                    $properties[] = $this->newScalar($datum, $depth);
                    continue;
                }
                if ('array' === $datum->typeName) {
                    if ($datum->isBuiltin) {
                        $properties[] = $this->newScalarArray($datum, $depth);
                        continue;
                    } else { //对象数组
                        $instance = $this->newObjectArray($datum, $depth);
                        $instance->className = $datum->arrayType;
                        $instance->child = $this->parserClassType($datum->arrayType, $depth + 1);
                        $properties[] = $instance;
                        continue;
                    }
                }

                if (class_exists($datum->typeName)) { //对象
                    $instance = $this->newObject($datum, $depth);
                    $instance->className = $datum->typeName;
                    $instance->child = $this->parserClassType($datum->typeName, $depth + 1);
                    $properties[] = $instance;
                    continue;
                }
                throw new \Exception("{$className}的{$key}属性异常");
            }

            return $properties;
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
    public function newScalar(PropertyInfo $info, int $depth)
    {
        return $this->new($info, $depth, 'scalar');
    }

    /**
     * 填充标量数组类型(非数组对象)
     * @param PropertyInfo $info
     * @param int $depth
     * @return ParameterParser
     * @throws \JsonException
     */
    public function newScalarArray(PropertyInfo $info, int $depth)
    {
        return $this->new($info, $depth, 'array');
    }

    /**
     * 填充对象
     * @param PropertyInfo $info
     * @param int $depth
     * @return ParameterParser
     * @throws \JsonException
     */
    public function newObject(PropertyInfo $info, int $depth)
    {
        return $this->new($info, $depth, 'object');
    }

    /**
     * 填充对象数组类型
     * @param PropertyInfo $info
     * @param int $depth
     * @return ParameterParser
     * @throws \JsonException
     */
    public function newObjectArray(PropertyInfo $info, int $depth)
    {
        return $this->new($info, $depth, 'object[]');
    }


    /**
     * @param PropertyInfo $info
     * @param int $depth
     * @param string $type scalar,array,object,object[]
     * @return ParameterParser
     * @throws \JsonException
     */
    public function new(PropertyInfo $info, int $depth, string $type = 'scalar')
    {
        $instance = new self();
        $instance->name = Str::uncamelize($info->name);
        $instance->type = match ($type) {
            'scalar' => $info->typeName,
            'array' => "{$info->arrayType}[]",
            'object' => 'object',
            'object[]' => $type,
        };
        $instance->isBuiltin = in_array($type, ['scalar', 'array'], true);
        $instance->hasDefaultValue = $info->hasDefaultValue;
        $instance->defaultValue = $info->defaultValue;
        $instance->document = $info->doc;
        $instance->isEnum = !empty($info->enumInfo);
        $instance->enumData = $instance->isEnum ? json_encode($info->enumInfo, JSON_THROW_ON_ERROR) : '';
        $instance->child = [];
        $instance->depth = $depth;
        $instance->isRequired = !($instance->hasDefaultValue || $info->allowsNull || $info->option);
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
            $arr .=$datum['label'].":".$datum['value'];
        }
       return json_encode($arr,',');
    }



}
