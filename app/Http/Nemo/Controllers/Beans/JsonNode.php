<?php

namespace App\Http\Nemo\Controllers\Beans;

use JetBrains\PhpStorm\ArrayShape;
use LaravelNemo\Library\Utils;
use LaravelNemo\Nemo;

class JsonNode extends Nemo
{
    /** name  */
    public string $name;

    /** int,float,string,bool,array,object */
    public string $type;

    /**  */
    public ?string $desc=null;

    /** @var   */
    public int $depth;

    /** @var JsonNode[]|null  */
    #[ArrayShape([JsonNode::class])]
    public ?array $children;

    /** mock */
    public ?string $mock = null;

    /** 数组类型 */
    public ?string $array_type = null;

    /** @var bool  */
    public bool $is_array_item;

    /** @var string  */
    public string $key;

    /** @var int 1 必须,0非必填 */
    public int $required;


    public function afterFill()
    {
       if($this->type === 'array' && !$this->is_array_item){
           if(empty($this->children)){
               $this->array_type = 'array';
           }else{
               $this->array_type = $this->getArrayType($this->children,Utils::camelize($this->name));
           }

       }
    }


    /**
     * @param JsonNode[] $children
     * @return string
     */
    private function getArrayType(array $children,$name=''):string{
        if($children[0]->type!=='array'){
            if($children[0]->type ==='object'){
                $children[0]->name = $name;
                return "{$name}[]";
            }
            return "{$children[0]->type}[]";
        }else{
            $type =  $this->getArrayType($children[0]->children,$name);
            return "{$type}[]";
        }

    }

}
