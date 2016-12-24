#!/usr/bin/env php
<?php

$count = 10000;

echo 'PHP: '.\PHP_VERSION.\PHP_EOL;
echo 'Count: '.$count.\PHP_EOL;

$tags = array('one', 'two', 'three', 'four', 'five');

$mem = \memory_get_usage();

$mt = \microtime(true);
$items = array();
for ($i = 0; $i < $count; $i++) {
    $items[] = array(
        'tag' => $tags[$i % 5],
        'value' => $i,
    );
}
$mt = \microtime(true) - $mt;

echo 'Create: '.$mt.\PHP_EOL;
echo 'Mem: '.\number_format((\memory_get_usage() - $mem), 0, ',', ' ').\PHP_EOL;

$sum = 0;
$mt = \microtime(true);
for ($i = 0; $i < 10; $i++) {
    foreach ($items as $item) {
        switch ($item['tag']) {
            case 'one':
                $sum += $item['value'];
                break;
            case 'two':
                $sum += \md5(\md5(($item['value'] * 2)));
                break;
            case 'three':
                $sum += $item['value'] * 3;
                break;
            case 'four':
                $sum += $item['value'] * 4;
                break;
            case 'five':
                $sum += $item['value'] * 5;
                break;                                                
        }
    }
}
$mt = \microtime(true) - $mt;

echo 'Process: '.$mt.\PHP_EOL;

