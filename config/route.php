<?php

return [

    'web' => [
        'name'=>'web',
        'prefix' => '',
        'namespace'=>'App\Http\Web\Controllers',
        'separator'=>'_'
    ],
    'api' => [
        'name'=>'api',
        'prefix' => 'api',
        'namespace'=>'App\Http\Api\Controllers',
        'separator'=>'_'
    ],
    'admin' => [
        'name'=>'admin',
        'prefix' => 'admin',
        'namespace'=>'App\Http\Admin\Controllers',
        'separator'=>'_'
    ]


];
