<?php

namespace App\Console\Tools;

use JetBrains\PhpStorm\ArrayShape;
use Majie\Fills\Fill\Proxy;

class ParameterParser
{

    /** @var string 参数名称(前端蛇形,php小驼峰) */
    protected string $name;

    /** @var string 类型 */
    protected string $type;

    /** @var bool 是否标量类型: int,string,bool,float,int[],string[],bool[],float[] */
    public bool $isBuiltin;

    /** @var bool 是否有默认值 */
    protected bool $hasDefaultValue;

    /** @var string 默认值 */
    protected string $defaultValue;

    /** @var string 文档 */
    protected string $document;

    /** @var bool 是否枚举 */
    protected bool $isEnum;

    /** @var string 枚举值 */
    protected string $enumData;

    /** @var array 子元素 */
    public array $child;

    /** @var int 元素层级深度 */
    public int $depth;


    public function __construct(protected \ReflectionParameter $parameter)
    {

    }


    public function parser($depth)
    {
        $this->name = $this->parameter->getName();
        $this->depth = $depth;

    }


    public function parseType()
    {
        $type = $this->parameter->getType();
        if (null === $type) {
            throw new \Exception("参数[{$this->name}]的类型不能为空");
        }

        if ($type instanceof \ReflectionNamedType) {
            $type = $type->getName();
            if ($this->isScalar($type)) {
                return $this->setParseTypeData($type, [], true);
            } elseif ('array' === $type) {//数组可以根据ArrayShape注解解析
                $attributes = $this->parameter->getAttributes(ArrayShape::class);
                if (empty($attributes)) {
                    return $this->setParseTypeData('[]', [], true);
                }

                $typeInArrayShape = $attributes[0]->getName();
                if ($this->isScalar($typeInArrayShape)) {
                    $type = "{$typeInArrayShape}[]";
                    return $this->setParseTypeData($type, [], true);
                }
                $typeInArrayShape = $attributes[0]->getName();
                $type = "{$typeInArrayShape}[]";
                $child = $this->parserClassType();
                return $this->setParseTypeData($type, [], true); //todo
            } else { //对象
                $child = $this->parserClassType(); //todo
            }
        }

        throw new \Exception("参数[{$this->name}]的声明类型不支持");
    }


    /**
     *
     * @param string $type
     * @param array $child
     * @param bool $isBuiltin
     * @return $this
     */
    private function setParseTypeData(string $type, array $child, bool $isBuiltin)
    {
        $this->child = $child;
        $this->isBuiltin = $isBuiltin;
        $this->type = $type;
        return $this;
    }


    /**
     * 是否标量
     * @param string $type
     * @return bool
     */
    private function isScalar(string $type): bool
    {
        return in_array($type, ['int', 'bool', 'string', 'float'], true);
    }


    private function parserClassType(string $className)
    {
        if (!class_exists($className)) {
            throw new \Exception("参数[{$this->name}]的数组注解类{$className}}不存在");
        }

        $instance = (new \ReflectionClass($className))->newInstance(null);
        if ($instance instanceof Proxy) {

        }
    }

}
