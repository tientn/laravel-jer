<?php

namespace LaravelSoft\JER;

use Illuminate\Foundation\Exceptions\Handler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionHandler extends Handler
{
    use ExceptionHandlerTrait;
}
