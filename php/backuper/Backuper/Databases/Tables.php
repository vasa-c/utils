<?php
/**
 * Бэкап базы с файлом на каждую таблицу
 *
 * @package Backuper
 * @author  Григорьев Олег aka vasa_c <go.vasac@gmail.com>
 */

namespace Backuper\Databases;

use Backuper\TablesList;
use Backuper\Helpers;
use Backuper\Dumper;

class Tables extends Base
{
    /**
     * @override
     */
    public function run()
    {
        $this->dbparams = $this->getDBParams();
        foreach ($this->getListTables() as $table) {
            $this->dumpTable($table);
        }
    }

    /**
     * Получить список таблиц из БД
     *
     * @return array
     */
    private function getListTables()
    {
        $list = new TablesList($this->dbparams);
        if ($this->params['show_tables_pdo']) {
            return $list->pdo();
        } else {
            return $list->cli();
        }
    }

    /**
     * Задампить одну таблицу
     *
     * @param string $table
     */
    private function dumpTable($table)
    {
        if (\in_array($table, $this->params['ignore_tables'])) {
            return false;
        }
        $nodata = \in_array($table, $this->params['ignore_data']);
        $vars = array(
            'db' => $this->params['dbname'],
            'table' => $table,
        );
        $filename = Helpers::tpl($this->params['table_filename'], $vars);
        $filename = Helpers::createPath($this->dir, $filename);
        if (!$this->checkFile($filename)) {
            return false;
        }
        $dumper = new Dumper($this->dbparams, $filename);
        $dumper->setTables(array($table));
        $dumper->setExtended($this->params['extended_insert']);
        $dumper->setNoData($nodata);
        $dumper->run();
    }

    /**
     * @var array
     */
    private $dbparams;
}
