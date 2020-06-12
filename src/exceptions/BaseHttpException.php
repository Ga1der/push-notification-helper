<?php

namespace src\exceptions;

/**
 * Class BaseHttpException
 *
 * @package src\exceptions
 * @property-read int $httpCode
 */
abstract class BaseHttpException extends BaseException
{
    protected $httpCode = 0;
}
