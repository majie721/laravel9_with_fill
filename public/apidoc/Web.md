[TOC]
# Web Api Document

## 1 个人中心2
### 1.1 用户信息2
- **接口说明：** 
- **接口地址：** index/demo2?ids=int
- **请求方式：** GET
#### 1.1.1 Query参数
| 参数名称 | 类型 | 是否必填 | 默认值 | 描述 |
| --- | --- | --- | --- | --- |
| ids | int | No | 222 | ID |
#### 1.1.2 Request Body
| 参数名称 | 类型 | 是否必填 | 默认值 | 描述 |
| --- | --- | --- | --- | --- |
| id | int | Yes |  | 订单ID |
| code | int | No |  | 订单号 |
| created_at | string | Yes |  | 订单创建时间 |
| status | int | Yes |  | 订单状态(枚举：【{"name":"App\\Http\\Enums\\DemoStatus","labelData":[{"label":"草稿","value":1},{"label":"处理中","value":2},{"label":"已完成","value":3}]}】) |
| product | object[] | Yes |  |  |
| &nbsp;&nbsp;--id | int | Yes |  | 商品id |
| &nbsp;&nbsp;--name | string | Yes |  | 商品名称 |
| &nbsp;&nbsp;--price | float | Yes |  | 商品价格 |
| &nbsp;&nbsp;--attrs | string[] | Yes |  | 商品属性 |
#### 1.1.3 Response Body
| 参数名称 | 类型 | 是否必填 | 默认值 | 描述 |
| --- | --- | --- | --- | --- |
| id | int | Yes |  | 订单ID |
| code | int | Yes |  | 订单号 |
| created_at | string | Yes |  | 订单创建时间 |
| status | int | Yes |  | 订单状态(枚举：【{"name":"App\\Http\\Enums\\DemoStatus","labelData":[{"label":"草稿","value":1},{"label":"处理中","value":2},{"label":"已完成","value":3}]}】) |
| product | object[] | Yes |  |  |
| &nbsp;&nbsp;--id | int | Yes |  | 商品id |
| &nbsp;&nbsp;--name | string | Yes |  | 商品名称 |
| &nbsp;&nbsp;--price | float | Yes |  | 商品价格 |
| &nbsp;&nbsp;--attrs | string[] | Yes |  | 商品属性 |
### 1.2 用户信息
- **接口说明：** 
- **接口地址：** index/user?a=bool&d=int&e=float
- **请求方式：** PUT
#### 1.2.1 Query参数
| 参数名称 | 类型 | 是否必填 | 默认值 | 描述 |
| --- | --- | --- | --- | --- |
| a | bool | Yes |  |  |
| d | int | Yes |  |  |
| e | float | Yes |  |  |
#### 1.2.2 Request Body
| 参数名称 | 类型 | 是否必填 | 默认值 | 描述 |
| --- | --- | --- | --- | --- |
#### 1.2.3 Response Body
| 参数名称 | 类型 | 是否必填 | 默认值 | 描述 |
| --- | --- | --- | --- | --- |
| id | int | Yes |  | 订单ID |
| code | int | Yes |  | 订单号 |
| created_at | string | Yes |  | 订单创建时间 |
| status | int | Yes |  | 订单状态(枚举：【{"name":"App\\Http\\Enums\\DemoStatus","labelData":[{"label":"草稿","value":1},{"label":"处理中","value":2},{"label":"已完成","value":3}]}】) |
| product | object[] | Yes |  |  |
| &nbsp;&nbsp;--id | int | Yes |  | 商品id |
| &nbsp;&nbsp;--name | string | Yes |  | 商品名称 |
| &nbsp;&nbsp;--price | float | Yes |  | 商品价格 |
| &nbsp;&nbsp;--attrs | string[] | Yes |  | 商品属性 |
### 1.3 用户信息
- **接口说明：** 
- **接口地址：** index/demo
- **请求方式：** GET
#### 1.3.1 Query参数
| 参数名称 | 类型 | 是否必填 | 默认值 | 描述 |
| --- | --- | --- | --- | --- |
#### 1.3.2 Request Body
| 参数名称 | 类型 | 是否必填 | 默认值 | 描述 |
| --- | --- | --- | --- | --- |
| id | int | Yes |  | 订单ID |
| code | int | No |  | 订单号 |
| created_at | string | Yes |  | 订单创建时间 |
| status | int | Yes |  | 订单状态(枚举：【{"name":"App\\Http\\Enums\\DemoStatus","labelData":[{"label":"草稿","value":1},{"label":"处理中","value":2},{"label":"已完成","value":3}]}】) |
| product | object[] | Yes |  |  |
| &nbsp;&nbsp;--id | int | Yes |  | 商品id |
| &nbsp;&nbsp;--name | string | Yes |  | 商品名称 |
| &nbsp;&nbsp;--price | float | Yes |  | 商品价格 |
| &nbsp;&nbsp;--attrs | string[] | Yes |  | 商品属性 |
#### 1.3.3 Response Body
| 参数名称 | 类型 | 是否必填 | 默认值 | 描述 |
| --- | --- | --- | --- | --- |
| id | int | Yes |  | 订单ID |
| code | int | Yes |  | 订单号 |
| created_at | string | Yes |  | 订单创建时间 |
| status | int | Yes |  | 订单状态(枚举：【{"name":"App\\Http\\Enums\\DemoStatus","labelData":[{"label":"草稿","value":1},{"label":"处理中","value":2},{"label":"已完成","value":3}]}】) |
| product | object[] | Yes |  |  |
| &nbsp;&nbsp;--id | int | Yes |  | 商品id |
| &nbsp;&nbsp;--name | string | Yes |  | 商品名称 |
| &nbsp;&nbsp;--price | float | Yes |  | 商品价格 |
| &nbsp;&nbsp;--attrs | string[] | Yes |  | 商品属性 |