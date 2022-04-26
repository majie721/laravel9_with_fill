<?php

return [
    'generateDocument' => [
        'Web'   => [
            'path'      => app_path('Http' . DIRECTORY_SEPARATOR . 'Web' . DIRECTORY_SEPARATOR . 'Controllers'),// "路由模块名称"=>"控制器文件夹"
            'separator' => '/', //前端访问的路由分割方式,eg:xxx.com/product_type/detail
        ],
        'Admin' => [
            'path' => app_path('Http' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'Controllers'), // "路由模块名称"=>"控制器文件夹"
            'separator' => '/', //前端访问的路由分割方式,eg:xxx.com/product_type/detail
        ],
        'Api'   => [
            'path' => app_path('Http' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . 'Controllers'),
            'separator' => '/', //前端访问的路由分割方式,eg:xxx.com/product_type/detail
        ]

    ]
];
