<?php

namespace src\exceptions;

use Throwable;

/**
 * Class HttpException
 *
 * @package src\exceptions
 */
final class HttpException extends BaseHttpException
{
    /**
     * HttpException constructor.
     *
     * @param int             $http_code
     * @param string          $message
     * @param int             $code
     * @param \Throwable|NULL $previous
     */
    public function __construct($http_code, $message = '', $code = 0, Throwable $previous = NULL)
    {
        $this->httpCode = $http_code;

        parent::__construct($message, $code, $previous);
    }
}