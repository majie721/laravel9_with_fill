<?php

namespace Majie\Fills\Test\TestClass;

use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{

    public function __construct(){
        parent::__construct();
        ini_set('xdebug.var_display_max_depth', -1);
        ini_set('xdebug.var_display_max_children', -1);
        ini_set('xdebug.var_display_max_data', -1);
    }

}