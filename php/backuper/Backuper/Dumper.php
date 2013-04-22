<?php
/**
 * Работа с mysqldump
 *
 * @package Backuper
 * @author  Григорьев Олег aka vasa_c <go.vasac@gmail.com>
 */

namespace Backuper;

class Dumper
{
    /**
     * Конструктор
     *
     * @param array $dbparams
     *        параметры подключения к БД
     * @param string $filename
     *        имя файла для записи дампа
     */
    public function __construct(array $dbparams, $filename)
    {
        $this->dbparams = $dbparams;
        $this->filename = $filename;
    }

    /**
     * Указать расширенный или не расширенный формат INSERT использовать
     *
     * @param bool $extended
     */
    public function setExtended($extended)
    {
        $this->extended = $extended;
    }

    /**
     * Указать список таблиц для дампа
     *
     * @param array $tables
     */
    public function setTables(array $tables)
    {
        $this->tables = $tables;
    }

    /**
     * Указать список таблиц, которые следует игнорировать
     *
     * @param array $tables
     */
    public function setIgnoreTables(array $tables)
    {
        $this->ignoreTables = $tables;
    }

    /**
     * Указать, следует ли дописывать дамп в файл или перезаписывать
     *
     * @param bool $append
     */
    public function setAppendFile($append)
    {
        $this->append = $append;
    }

    /**
     * Указать, следует ли сохранять данные
     *
     * @param bool $nodata
     */
    public function setNoData($nodata)
    {
        $this->nodata = $nodata;
    }

    /**
     * Настройки камментов в дампе
     *
     * @param bool $comments
     *        добавлять камменты
     * @param bool $date
     *        добавлять время дампа
     */
    public function setComments($comments, $date)
    {
        $this->comments = $comments;
        $this->date = $date;
    }

    /**
     * Выполнить команду
     */
    public function run()
    {
        $this->options = array();
        $this->addOption('host', $this->dbparams['host']);
        $this->addOption('port', $this->dbparams['port']);
        $this->addOption('user', $this->dbparams['username']);
        $this->addOption('password', $this->dbparams['password']);
        $this->addOption('extended-insert', $this->extended);
        $this->addOption('comments', $this->comments);
        $this->addOption('dump_date', $this->date);
        if ($this->nodata) {
            $this->addOption('no-data', '');
        }
        foreach ($this->ignoreTables as $table) {
            $this->addOption('ignore-table', $this->dbparams['dbname'].'.'.$table);
        }
        $cmd = 'mysqldump '.\implode(' ', $this->options).' '.$this->dbparams['dbname'];
        if (!empty($this->tables)) {
            $cmd .= ' '.\implode(' ', $this->tables);
        }
        $cmd .= ($this->append ? ' >> ' : ' > ').$this->filename;
        Helpers::exec($cmd);
    }

    private function addOption($key, $value)
    {
        if (\is_null($value)) {
            return;
        }
        if ($value === true) {
            $value = 'TRUE';
        } elseif ($value === false) {
            $value = 'FALSE';
        }
        if ($value !== '') {
            $value = '='.$value;
        }
        $this->options[] = '--'.$key.$value;
    }

    /**
     * @var array
     */
    private $dbparams;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var bool
     */
    private $extended = true;

    /**
     * @var array
     */
    private $tables = array();

    /**
     * @var array
     */
    private $ignoreTables = array();

    /**
     * @var bool
     */
    private $append = false;

    /**
     * @var bool
     */
    private $nodata = false;

    /**
     * @var array
     */
    private $options;

    /**
     * @var bool
     */
    private $comments = false;

    /**
     * @var bool
     */
    private $date = false;
}
