<?php

namespace LaLu\JER;

use Illuminate\Contracts\Debug\ExceptionHandler as BaseExceptionHandler;
use Psr\Log\LoggerInterface;

class ExceptionHandler implements BaseExceptionHandler
{
    use ExceptionHandlerTrait;

    /**
     * Create a new exception handler instance.
     *
     * @param \Psr\Log\LoggerInterface $log
     */
    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }
}
