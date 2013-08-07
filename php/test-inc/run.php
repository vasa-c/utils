#!/usr/bin/env php
<?php

require_once(__DIR__.'/autoload.php');

class Test
{
    public function __construct($dir)
    {
        $this->dir = $dir;
    }
    
    public function run($test)
    {
        $mems = \memory_get_usage();
        $mt = \microtime(true);
        switch ($test) {
            case 'autoload':
                $this->testAutoload();
                break;
            case 'full':
                $this->testFull();
                break;
            case 'req':
                $this->testReq();
                break;  
            case 'phar-autoload':
                $this->testPharAutoload();
                break; 
            case 'phar-bz':
                $this->testPharBZ();
                break;   
            case 'phar-gz':
                $this->testPharGZ();
                break;                                                                              
            case 'phar-req':
                $this->testPharReq();
                break;
            default:
                echo 'WTF? '.$test.'?'.\PHP_EOL;
                break;
        }
        $mt = \microtime(true) - $mt;
        $meme = \memory_get_usage();
        echo 'MT: '.\number_format($mt * 1000, 3, ',', ' ').' ms'.\PHP_EOL;        
        echo 'MEM: '.\number_format(round($meme / 1024), 0, ',', ' ').' K'.
            '(d='.\number_format(round(($meme - $mems) / 1024), 0, ',', ' ').')'.\PHP_EOL;
    }
    
    private function testAutoload()
    {
        $autoload = new Autoloader($this->dir.'/classes');
        $autoload->register();
        require($this->dir.'/exec.php');
    }
    
    private function testFull()
    {
        require($this->dir.'/full.php');
        require($this->dir.'/exec.php');
    }
    
    private function testReq()
    {
        require($this->dir.'/req.php');
        require($this->dir.'/exec.php');    
    }
    
    private function testPharAutoload()
    {
        $autoload = new Autoloader('phar:///'.$this->dir.'/phar-none.phar');
        $autoload->register();
        require($this->dir.'/exec.php');    
    }
    
    private function testPharReq()
    {
        require($this->dir.'/phar-req.php');
        require($this->dir.'/exec.php');       
    }
    
    private function testPharGZ()
    {
        $autoload = new Autoloader('phar:///'.$this->dir.'/phar-gz.phar');
        $autoload->register();
        require($this->dir.'/exec.php');     
    }    
    
    private function testPharBZ()
    {
        $autoload = new Autoloader('phar:///'.$this->dir.'/phar-bz.phar');
        $autoload->register();
        require($this->dir.'/exec.php');     
    }        
    
    private $dir;
}

$args = isset($_SERVER['argv']) ? $_SERVER['argv'] : array();
if (isset($args[1])) {
    $test = $args[1];
} elseif (isset($_GET['test'])) {
    $test = $_GET['test'];
} else {
    echo 'Format: ./run.php (autoload|full|req|phar-autoload|phar-req|phar-gz|phar-bz)'.\PHP_EOL;
    exit();
}

$itest = new Test(__DIR__.'/test');
$itest->run($test);

