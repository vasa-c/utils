<?php
/**
 * Базовый класс, бэкапящий одну базу
 *
 * @package Backuper
 * @author  Григорьев Олег aka vasa_c <go.vasac@gmail.com>
 */

namespace Backuper\Databases;

abstract class Base
{
    /**
     * Получить бэкапер нужного типа
     *
     * @param string $dir
     * @param string $name
     * @param array $params
     * @return \Backuper\Databases\classname
     */
    public static function getInstanceByParams($dir, $name, array $params)
    {
        $classname = $params['single_file'] ? 'Single' : 'Tables';
        $classname = __NAMESPACE__.'\\'.$classname;
        return new $classname($dir, $name, $params);
    }

    /**
     * Конструктор
     *
     * @param string $dir
     *        корневой каталог бэкапа
     * @param string $name
     *        имя базы
     * @param array $params
     *        параметры базы
     */
    public function __construct($dir, $name, array $params)
    {

    }

    /**
     * @var string
     */
    private $dir;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $params;
}
