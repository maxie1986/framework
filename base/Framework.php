<?php

namespace Framework\Base;

use Illuminate\Database\Capsule\Manager as Capsule;

// Set the event dispatcher used by Eloquent models... (optional)
//use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Symfony\Component\HttpFoundation\Request;

class Framework
{

    /**
     * @var $capsule Capsule
     */
    public static $capsule;


    /**
     * @var $request Request
     */
    public static $request;

    /**
     * @var $router Router
     */
    public static $router;



    public static function run()
    {
        self::init();
        self::config();
        self::initDb();
        self::dispatch();
    }

    private static function init()
    {
        defined('FRAMEWORK_PATH') or define('FRAMEWORK_PATH', ROOT . DS . 'framework');
        defined('APPLICATION_PATH') or define('APPLICATION_PATH', ROOT . DS . 'app');
        defined('PUBLIC_PATH') or define("PUBLIC_PATH", ROOT . DS . "public" . DS);
        defined('CONFIG_PATH') or define("CONFIG_PATH", APPLICATION_PATH . DS . "config" . DS);
        defined('CONTROLLER_PATH') or define("CONTROLLER_PATH", APPLICATION_PATH . DS . "controllers" . DS);
        defined('MODEL_PATH') or define("MODEL_PATH", APPLICATION_PATH . DS . "models" . DS);
        defined('VIEW_PATH') or define("VIEW_PATH", APPLICATION_PATH . DS . "views" . DS);

        self::$request = Request::createFromGlobals();

    }

    private static function config()
    {

        $files = scandir(CONFIG_PATH);
        $config = [];
        foreach ($files as $file) {
            if (is_file(CONFIG_PATH . $file) && str_contains($file, '.php')) {
                $currentConfig = require_once CONFIG_PATH . $file;
                $config = array_merge($config, $currentConfig);
            }

        }

        Config::setValues($config);
    }


    public static function initDb()
    {
        if (!empty(self::$capsule)) {
            return;
        }
        $capsule = new Capsule;

        $dbConfig = Config::get('db');

        if ($dbConfig && isset($dbConfig['mysql'])) {
            $capsule->addConnection($dbConfig['mysql']);
        } else {
            throw new \Exception('Error while connecting database');
        }
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        self::$capsule = $capsule;
    }

    /**
     * @return mixed
     */
    public static function capsule()
    {
        return self::$capsule;
    }

    /**
     * @return Request
     */
    public static function getRequest()
    {
        return self::$request;
    }

    public static function dispatch()
    {
        self::$router = new Router(self::$request);
    }

}