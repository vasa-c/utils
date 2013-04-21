<?php
/**
 * Загрузчик классов
 *
 * @package Backuper
 * @author  Григорьев Олег aka vasa_c <go.vasac@gmail.com>
 */

namespace Backuper;

class Autoloader
{
    public static function loadClassByName($classname)
    {
        if (\strpos($classname, __NAMESPACE__.'\\') !== 0) {
            return false;
        }
        $classname = \substr($classname, \strlen(__NAMESPACE__) + 1);
        $filename = \str_replace('\\', \DIRECTORY_SEPARATOR, $classname);
        $filename = __DIR__.\DIRECTORY_SEPARATOR.$filename.'.php';
        if (!\file_exists($filename)) {
            return false;
        }
        require_once($filename);
        return true;
    }

    public static function register()
    {
        \spl_autoload_register(array(__CLASS__, 'loadClassByName'));
    }
}
