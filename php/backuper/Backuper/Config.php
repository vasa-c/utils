<?php
/**
 * Надстройка над конфигом
 *
 * @package Backuper
 * @author  Григорьев Олег aka vasa_c <go.vasac@gmail.com>
 */

namespace Backuper;

class Config
{
    /**
     * Конструктор
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Получить отдельный параметр
     *
     * @param string $name
     * @param mixed $default [optional]
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return \array_key_exists($name, $this->config) ? $this->config[$name] : $default;
    }

    /**
     * Получить список баз с нормализованными параметрами
     *
     * @return array
     */
    public function getDatabases()
    {
        if (!$this->databases) {
            $this->databases = array();
            foreach ($this->config['databases'] as $name => $params) {
                $this->databases[$name] = $this->normalizeDatabase($params);
            }
        }
        return $this->databases;
    }

    /**
     * Заполнить пропущенные параметры БД параметрами по умолчанию
     *
     * @param array $params
     * @return array
     */
    private function normalizeDatabase(array $params)
    {
        return \array_merge($this->config['defaults'], $params);
    }

    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $databases;
}
