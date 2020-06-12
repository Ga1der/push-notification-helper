<?php

namespace src\exceptions;

use Exception;

/**
 * Class BaseException
 *
 * @package src\exceptions
 * @property-read string    name
 * @property-read string    message
 * @property-read string    file
 * @property-read int       line
 * @property-read int       code
 * @property-read array     trace
 * @property-read string    traceAsString
 * @property-read Exception previous
 */
abstract class BaseException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        $class     = get_class($this);
        $lastSlash = strrpos($class, '\\', 0);
        $lastSlash = intval($lastSlash);
        $className = substr($class, $lastSlash, NULL);
        $className = strval($className);

        return $className;
    }

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if (method_exists($this, "get{$name}")) {
            /** @var mixed $result */
            $result = $this->{"get{$name}"}();

            return $result;
        }

        return NULL;
    }
}
