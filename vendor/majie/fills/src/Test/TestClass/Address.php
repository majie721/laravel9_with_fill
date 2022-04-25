<?php

namespace Majie\Fills\Test\TestClass;

use Majie\Fills\Fill\Proxy;

class Address  extends Proxy
{
    #[Doc('名')]
    public string $firstName;

    #[Doc('名')]
    public string $lastName;

    #[Doc('公司')]
    public string $company;

    #[Doc('地址')]
    public string $address_1;
}