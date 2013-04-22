#!/usr/bin/env php
<?php
/**
 * Создание бэкапа - исполняемый файл (на крон нужно ставить его)
 *
 * @author Григорьев Олег aka vasa_c <go.vasac@gmail.com>
 */

require_once(__DIR__.'/Backuper/Autoloader.php');
\Backuper\Autoloader::register();

$config = include(__DIR__.'/config.php');
$backuper = new \Backuper\Backuper($config);
$backuper->run();
