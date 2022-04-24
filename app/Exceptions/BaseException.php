<?php

namespace App\Exceptions;

use App\Helpers\Logger;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\Pure;
use Exception;

abstract class BaseException extends Exception
{

    /**
     * @param string $message
     * @param int $apiCode
     * @param int $httpCode
     * @param \Throwable|null $previous
     */
    public function __construct(string                $message = "",
                                protected int         $apiCode = 500,
                                protected int         $httpCode = 500,
                                protected ?\Throwable $previous = null)
    {
        parent::__construct($message, $apiCode, $previous);
    }


    public function render($request)
    {

        return \response($this->getMessage(), $this->getHttpCode());
    }


    public function report()
    {
        Logger::exception()->error($this->getMessage());
    }

    /**
     * @return string
     */
    public function responseMessage(): string
    {
        $this->getMessage();
    }

    /**
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
