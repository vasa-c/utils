<?php
/**
 * Получение списка таблиц
 *
 * @package Backuper
 * @author  Григорьев Олег aka vasa_c <go.vasac@gmail.com>
 */

namespace Backuper;

class TablesList
{
    /**
     * Конструктор
     *
     * @param array $dbparams
     *        параметры подключения
     */
    public function __construct($dbparams)
    {
        $this->dbparams = $dbparams;
    }

    /**
     * Получение списка через PDO
     *
     * @return array
     */
    public function pdo()
    {
        $pdo = new \PDO($this->getDSNForPDO(), $this->dbparams['username'], $this->dbparams['password']);
        $result = $pdo->query('SHOW TABLES');
        $tables = $result->fetchAll(\PDO::FETCH_COLUMN);
        $result->closeCursor();
        return $tables;
    }

    /**
     * Получение списка через системный вызов
     *
     * @return array
     */
    public function cli()
    {
        $params = array(
            'host' => $this->dbparams['host'],
            'port' => $this->dbparams['port'],
            'user' => $this->dbparams['username'],
            'password' => $this->dbparams['password'],
        );
        $options = array();
        foreach ($params as $k => $v) {
            if (!empty($v)) {
                $options[] = '--'.$k.'='.$v;
            }
        }
        $cmd = 'echo "SHOW TABLES" | mysql '.\implode(' ', $options).' '.$this->dbparams['dbname'];
        Helpers::exec($cmd, $output);
        @\array_shift($output);
        return $output;
    }

    /**
     * @return string
     */
    private function getDSNForPDO()
    {
        $dsn = array();
        foreach (array('host', 'port', 'dbname') as $field) {
            if (isset($this->dbparams[$field])) {
                $dsn[] = $field.'='.$this->dbparams[$field];
            }
        }
        return 'mysql:'.\implode(';', $dsn);
    }

    /**
     * @var array
     */
    private $dbparams;
}
