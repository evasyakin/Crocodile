<?php
/**
* Singleton PSR4 Loader
* success work on php version 7
* @author Egor Vasyakin <egor.vasyakin@itevas.ru>
*/

class Loader
{
    /**
    * @var array directories
    */
    public static $dirs = [];

    /**
    * @var array namespaces
    */
    public static $namespaces = [];

    /**
    * @var boolean registered loader
    */
    public static $_registered = false;

    /**
    * @var string base directory
    */
    public static $baseDir = '';

    /**
    * register directory
    * @param string directory
    */
    public static function registerDir(string $dir)
    {
        static::$dirs[] = $dir;
    }

    /**
    * register some directories
    * @param array directories
    */
    public static function registerDirs(array $dirs)
    {
        foreach ($dirs as $dir) {
            static::registerDir($dir);
        }
    }

    /**
    * register namespace
    * @param string namespace
    * @param string directory
    */
    public static function registerNamespace(string $namespace, string $dir)
    {
       static::$namespaces[$namespace] = $dir;
    }

    /**
    * register some namespaces
    * @param array namespaces
    */
    public static function registerNamespaces(array $namespaces)
    {
        foreach ($namespaces as $namespace => $dir) {
            static::registerNamespace($namespace, $dir);
        }
    }

    /**
    * register loader
    */
    public static function register()
    {
        static::registerDir('vendor/');
        static::$_registered = true;
        spl_autoload_register('static::autoload');
    }

    /**
    * unregister loader
    */
    public function unregister()
    {
        static::$_registered = false;
        spl_autoload_unregister('static::autoload');
    }

    /**
    * registered loader
    * @return boolean registered
    */
    public static function registered()
    {
        return static::$_registered;
    }

    /**
    * autoload
    * @param string class name
    */
    public static function autoload(string $class)
    {
       $class = str_replace('\\', '/', $class) . '.php';
        foreach (static::$namespaces as $namespace => $dir) {
            if (strpos($class, $namespace) === 0) {
                $filename = static::$baseDir . str_replace($namespace, $dir, $class);
                if (is_readable($filename)) {
                    return include $filename;
                }
            }
        }
        foreach (static::$dirs as $dir) {
            $filename = static::$baseDir . $dir . $class;
            if (is_readable($filename)) {
                return include $filename;
            }
        }
    }

}