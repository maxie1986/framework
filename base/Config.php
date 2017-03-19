<?php
/**
 * A generic config class to be accessed everywhere
 */

namespace Framework\Base;


class Config
{
    /**
     * @var Config
     */
    private static $instance;

    /**
     * @var array
     */
    private static $values = [];

    /**
     * Instantiation can be done only inside the class itself
     * @param array $values
     */
    private function __construct($values = [])
    {
        self::$values = $values;
    }

    /**
     * @return Config
     * @internal param array $values
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Set configuration value by key.
     *
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        self::$values[$key] = $value;
    }

    /**
     * Get configuration value by key.
     *
     * @param string $key
     * @return mixed
     */
    public static function get($key = null)
    {
        if(is_null($key)) {
            return self::$values;
        }

        if (isset(self::$values[$key])) {
            return self::$values[$key];
        }

        return null;
    }

    /**
     * @param array $values
     */
    public static function setValues($values = [])
    {
        $instance = self::getInstance();
        $instance::$values = $values;
    }

    /**
     * Cloning singleton is not possible.
     *
     * @throws \Exception
     */
    public function __clone()
    {
        throw new \Exception('You cannot clone singleton object');
    }
}
