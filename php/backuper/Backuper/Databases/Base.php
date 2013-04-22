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
     * @return \Backuper\Databases\Base
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
        $this->dir = $dir;
        $this->name = $name;
        $this->params = $params;
    }

    /**
     * Забэкапить базу
     */
    abstract public function run();

    /**
     * Получить параметры для подключения к базе
     *
     * @return array
     */
    protected function getDBParams()
    {
        $params = array();
        foreach (array('host', 'username', 'password', 'dbname', 'port') as $field) {
            $params[$field] = isset($this->params[$field]) ? $this->params[$field] : null;
        }
        return $params;
    }

    /**
     * @var string
     */
    protected $dir;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $params;
}
