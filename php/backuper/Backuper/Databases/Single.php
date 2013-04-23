<?php
/**
 * Бэкап базы одним файлом
 *
 * @package Backuper
 * @author  Григорьев Олег aka vasa_c <go.vasac@gmail.com>
 */

namespace Backuper\Databases;

use Backuper\Helpers;
use Backuper\Dumper;

class Single extends Base
{
    /**
     * @override
     */
    public function run()
    {
        $params = $this->getDBParams();
        $filename = $this->getFilename(true);
        if (!$this->checkFile($filename)) {
            return;
        }
        $append = $this->dumpOnlyStruct($params, $filename);
        $this->dumpOther($params, $filename, $append);
    }


    /**
     * Задампить таблицы, где требуется только структура
     */
    private function dumpOnlyStruct($params, $filename)
    {
        if (empty($this->params['ignore_data'])) {
            return false;
        }
        $dumper = new Dumper($params, $filename);
        $dumper->setTables($this->params['ignore_data']);
        $dumper->setNoData(true);
        $dumper->run();
        return true;
    }

    /**
     * Задампить всё остальное
     */
    private function dumpOther($params, $filename, $append)
    {
        $dumper = new Dumper($params, $filename);
        $dumper->setAppendFile($append);
        if (!empty($this->params['tables'])) {
            $dumper->setTables($this->params['tables']);
        }
        $ignore = \array_merge($this->params['ignore_tables'], $this->params['ignore_data']);
        $dumper->setIgnoreTables($ignore);
        $dumper->run();
    }

    /**
     * Получить имя файла для дампа
     *
     * @param bool $create
     *        создать недостающий путь
     * @return string
     */
    private function getFilename($create)
    {
        $vars = array('db' => $this->name);
        $filename = Helpers::tpl($this->params['single_filename'], $vars);
        return Helpers::createPath($this->dir, $filename);
    }
}
