<?php
/**
 * Различные вспомогательные функции
 *
 * @package Backuper
 * @author  Григорьев Олег aka vasa_c <go.vasac@gmail.com>
 */

namespace Backuper;

class Helpers
{
    /**
     * Выполнить системную команду
     *
     * @param string $cmd
     * @return int
     */
    public static function exec($cmd)
    {
        echo $cmd.\PHP_EOL;
        \system($cmd, $ret);
        return $ret;
    }

    /**
     * Шаблонизатор
     *
     * @param string $template
     * @param array $vars
     * @return string
     */
    public static function tpl($template, array $vars)
    {
        foreach ($vars as $k => $v) {
            $template = \str_replace('{{ '.$k.' }}', $v, $template);
        }
        return $template;
    }

    /**
     * Создать все недостающие каталоги
     *
     * @param string $dir
     *        базовый каталог
     * @param string $filename
     *        имя файла
     */
    public static function createPath($dir, $filename)
    {
        return $dir.\DIRECTORY_SEPARATOR.$filename;
    }
}
