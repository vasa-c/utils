<?php
/**
 * Главный класс
 *
 * @package Backuper
 * @author  Григорьев Олег aka vasa_c <go.vasac@gmail.com>
 */

namespace Backuper;

class Backuper
{
    /**
     * Конструктор
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * Бэкапить!
     */
    public function run()
    {
        $this->backupDatabases();
        $this->save();
    }

    /**
     * Забэкапить все базы
     */
    private function backupDatabases()
    {
        $dir = $this->config->get('dir');
        foreach ($this->config->getDatabases() as $name => $params) {
            Databases\Base::getInstanceByParams($dir, $name, $params)->run();
        }
    }

    /**
     * Сохранить бэкапы
     */
    private function save()
    {

    }

    /**
     * @var \Backuper\Config
     */
    private $config;
}
