#!/usr/bin/env php
<?php
/**
 * Создание бэкапа - исполняемый файл (на крон нужно ставить его)
 *
 * @author Григорьев Олег aka vasa_c <go.vasac@gmail.com>
 */

$format = 'Format: backup.php [--config=CONFIG_FILE]';

$args = $_SERVER['argv'];
switch (\count($args)) {
    case 1:
        $configFilename = __DIR__.'/config.php';
        break;
    case 2:
        if (!\preg_match('/^\-\-config\=(.*+)$/', $args[1], $matches)) {
            echo $format.\PHP_EOL;
            exit();
        }
        $configFilename = $matches[1];
        break;
    default:
        echo $format.\PHP_EOL;
        exit();
}

if (!\file_exists($configFilename)) {
    echo 'Config file is not found'.\PHP_EOL;
    exit();
}
$config = include($configFilename);

require_once(__DIR__.'/Backuper/Autoloader.php');
\Backuper\Autoloader::register();

$backuper = new \Backuper\Backuper($config);
$backuper->run();
