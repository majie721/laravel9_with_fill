<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class Logger
{
    public static function exception():LoggerInterface
    {
        return Log::channel(__FUNCTION__);
    }
}
