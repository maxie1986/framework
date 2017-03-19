<?php

namespace Framework\Base;

class Autoloader
{

    
    // Autoloading
    public static function autoload()
    {
        spl_autoload_register('Autoloader::load');
    }


// Define a custom load method

    private static function load($classname)
    {

        $filename = str_replace("\\", '/', $classname). ".php";
        if (file_exists($filename)) {
            include($filename);
            if (class_exists($className)) {
                return TRUE;
            }
        }
        return FALSE;

        // Here simply autoload app’s controller and model classes
        if (substr($classname, -10) == "Controller") {// Controller
            require_once ROOT . DS .CONTROLLER_PATH . "$classname.class.php";
        } elseif (substr($classname, -5) == "Model") {
            // Model
            require_once MODEL_PATH . "$classname.class.php";
        }
    }
}