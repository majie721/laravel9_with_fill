<?php

namespace App\Console\Tools;



interface GeneratorInterface
{
    public function generate(): FileStore;
}

