#!/usr/bin/env php
<?php

$count = 10000;

echo 'PHP: '.\PHP_VERSION.\PHP_EOL;
echo 'Count: '.$count.\PHP_EOL;

$mem = \memory_get_usage();

$mt = \microtime(true);
$items = array();
for ($i = 0; $i < $count; $i++) {
    $items[] = array($i % 5, $i);
}
$mt = \microtime(true) - $mt;

echo 'Create: '.$mt.\PHP_EOL;
echo 'Mem: '.\number_format((\memory_get_usage() - $mem), 0, ',', ' ').\PHP_EOL;

$sum = 0;
$mt = \microtime(true);
for ($i = 0; $i < 10; $i++) {
    foreach ($items as $item) {
        switch ($item[0]) {
            case 0:
                $sum += $item[1];
                break;
            case 1:
                $sum += \md5(\md5(($item['1'] * 2)));
                break;
            case 2:
                $sum += $item[1] * 3;
                break;
            case 3:
                $sum += $item[1] * 4;
                break;
            case 4:
                $sum += $item[1] * 5;
                break;                                                
        }
    }
}
$mt = \microtime(true) - $mt;

echo 'Process: '.$mt.\PHP_EOL;

