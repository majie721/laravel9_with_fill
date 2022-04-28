<?php

namespace Majie\Fills\Fill\AttributeClass;
/**
 * 用来生成api文档
 */
#[\Attribute]
class ApiDoc
{


    /**
     * @param string $module 模块名称
     * @param string $name   接口名称
     * @param string $response 响应的数据的对象
     * @param string $desc 描述
     * @param string $method 请求方式
     * @param int $sort 排序[by desc]
     */
    public function __construct(
       public string $module='',
       public string $name='',
       public string $response='',
       public string $desc='',
       public string $method='POST',
       public int    $sort=100,
    )
    {

    }
}
