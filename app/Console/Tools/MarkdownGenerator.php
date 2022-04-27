<?php

namespace App\Console\Tools;


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

                $lines[] = "#### {$apiIndex}.1 请求参数";
                $lines[] = "| 参数名称 | 类型 | 是否必填 | 默认值 | 描述 |";
                $lines[] = "| --- | --- | --- | --- | --- |";
                $paramLines =  $this->getLine($doc->requestParam);
                $lines = [...$lines,...$paramLines];
            }

        }

        $content = implode(PHP_EOL, $lines);
        return new FileStore($content, 'md');
    }


    /**
     * @param ParameterParser[] $paramsList
     * @return array
     */
    public function getLine(array $paramsList):array{
        $elements = [];
        /** @var ParameterParser $item */
        foreach ($paramsList as $item){
            $name = self::placeholder($item->depth-1).$item->name;
            $type = $item->type;
            $required = $item->isRequired ? 'Yes':'No';
            $default = $item->hasDefaultValue?$item->defaultValue:'';
            $desc = $item->document;
            if($item->isEnum){
                $enumData = json_encode(json_decode($item->enumData),JSON_UNESCAPED_UNICODE);
                $desc .= "(枚举：【{$enumData}】)";
            }
            $elements[] = "| $name | $type | $required | $default | $desc |";
            if($item->child){
                $elements = [...$elements,...$this->getLine($item->child)];
            }
       }
        return  $elements;
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
       return str_pad("&nbsp;",$n*2);
    }

}
