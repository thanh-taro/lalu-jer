<?php

namespace LaLu\JER;

use Illuminate\Foundation\Exceptions\Handler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionHandler extends Handler
{
    use ExceptionHandlerTrait;
}
