<?php

namespace Majie\Fills\Test;

use Majie\Fills\Fill\PropertyParser;
use Majie\Fills\Test\TestClass\BaseTest;
use Majie\Fills\Test\TestClass\Order;
use Majie\Fills\Test\TestClass\StatusEnum;
use PHPUnit\Framework\TestCase;

Class EnumTest extends BaseTest{

    public function testArrayAccess(){
        var_dump(StatusEnum::COMPLETE['name']);
        var_dump(StatusEnum::COMPLETE['value']);
        var_dump(StatusEnum::COMPLETE['label']);
        var_dump(StatusEnum::COMPLETE['undefind']);

        var_dump(StatusEnum::COMPLETE['value'] ==3);
        $this->assertTrue(true);
    }

    public function testValues(){
        var_dump(StatusEnum::values());
    }

    public function testTryFrom(){
        var_dump(StatusEnum::tryFrom('09')?->value === 3);
    }

    public function testLabelData(){
        var_dump(StatusEnum::labelData());
    }




}