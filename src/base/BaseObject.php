<?php

namespace src\base;

use src\exceptions\InaccessiblePropertyException;
use src\exceptions\InvalidPropertyException;

/**
 * Class BaseObject
 *
 * @package yii\base
 */
abstract class BaseObject
{
    /**
     * BaseObject constructor.
     *
     * @param array $config
     */
    final public function __construct(array $config = [])
    {
        self::configure($this, $config);
        $this->init();
    }

    /**
     * @param \src\base\BaseObject $object
     * @param array                $properties
     *
     * @return \src\base\BaseObject
     */
    final public static function configure(self $object, array $properties)
    {
        foreach ($properties as $name => $value) {
            $object->{$name} = $value;
        }

        return $object;
    }

    /**
     *
     */
    public function init()
    {
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws \src\exceptions\InaccessiblePropertyException
     * @throws \src\exceptions\InvalidPropertyException
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        $setter = 'set' . $name;
        if (!method_exists($this, $getter) && method_exists($this, $setter))
            throw new InaccessiblePropertyException(__METHOD__, __LINE__);
        if (!method_exists($this, $getter))
            throw new InvalidPropertyException(__METHOD__, __LINE__);

        return $this->{$getter}();
    }

    /**
     * @param $name
     * @param $value
     *
     * @throws \src\exceptions\InaccessiblePropertyException
     * @throws \src\exceptions\InvalidPropertyException
     */
    public function __set($name, $value)
    {
        $getter = 'get' . $name;
        $setter = 'set' . $name;
        if (!method_exists($this, $setter) && method_exists($this, $getter))
            throw new InaccessiblePropertyException(__METHOD__, __LINE__);
        if (!method_exists($this, $setter))
            throw new InvalidPropertyException(__METHOD__, __LINE__);

        $this->{$setter}($value);
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (!method_exists($this, $getter)) return FALSE;

        return !is_null($this->$getter());
    }

    /**
     * @param $name
     *
     * @throws \src\exceptions\InaccessiblePropertyException
     * @throws \src\exceptions\InvalidPropertyException
     */
    public function __unset($name)
    {
        $getter = 'get' . $name;
        $setter = 'set' . $name;
        if (!method_exists($this, $setter) && method_exists($this, $getter))
            throw new InaccessiblePropertyException(__METHOD__, __LINE__);
        if (!method_exists($this, $setter))
            throw new InvalidPropertyException(__METHOD__, __LINE__);

        $this->{$setter}(NULL);
    }
}
