#!/usr/bin/env php
<?php

class Creator
{
    public function __construct($dir)
    {
        $this->dir = $dir;    
    } 
    
    public function run() 
    {
        $this->classes = array();    
        $this->createClassesList();
        \mkdir($this->dir);
        $this->createClassesFiles();
        $this->createExec();
        $this->createRequire();
        $this->createPhar();
        $this->createPharReq();
    }
   
    private function createClassesList($ns = null)
    {
        for ($i = 0; $i < 5; $i++) {
            $name = $this->getRandomClassName();
            if (!$ns) {                
                $this->createClassesList($name);
            } else {
                $name = $ns.'\\'.$name;
            }
            for ($j = 0; $j < 5; $j++) {
                $this->classes[] = $name.'\\'.$this->getRandomClassName();
            }            
        }
        \shuffle($this->classes);
        $this->uses = \array_slice($this->classes, 0, 50);
    }
    
    private function createClassesFiles()
    {
        $prefix = $this->dir.'/classes';
        \mkdir($prefix);
        $full = [];
        $tag = '<?php'.\PHP_EOL;
        foreach ($this->classes as $cn) {
            $cn = \explode('\\', $cn);
            $d = $prefix.'/'.$cn[0];
            if (!file_exists($d)) {
                \mkdir($d);
            }
            if (\count($cn) === 3) {
                $d .= '/'.$cn[1];
                if (!file_exists($d)) {
                    \mkdir($d);
                }                
            }
            $d .= '/'.$cn[\count($cn) - 1].'.php';
            $content = $this->getClassContent($cn);
            $full[] = $content;
            \file_put_contents($d, $tag.$content);
        }
        \file_put_contents($this->dir.'/full.php', $tag.\implode(\PHP_EOL, $full));
    }
    
    private function getClassContent($classname)
    {
        $base = \array_pop($classname);
        $ns = \implode('\\', $classname);
        $content = [];
        $content[] = '/**';
        $content[] = ' * @package: '.$ns;
        $content[] = ' * @subpackage: '.$base;
        $content[] = ' */';
        $content[] = '';
        $content[] = 'namespace '.$ns.';';        
        $content[] = '';
        $content[] = 'class '.$base.' {';
        $content[] = '';
        $content[] = '    public static function test($x) {return $x + '.\mt_rand(0, 100).';}';
        foreach (['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'] as $l) {
            $content[] = '';
            $content[] = '    public function '.$this->getRandomMethodName().'($'.$l.') {return $'.$l.' + '.\mt_rand(0, 100).';}';
        }
        $content[] = '}';
        $content[] = '';        
        return \implode(\PHP_EOL, $content);        
    }   
  
    private function createExec()
    {
        $content = ['<?php'];
        $content[] = '$sum = 0;';        
        $A = \array_merge($this->uses, $this->uses, $this->uses);
        shuffle($A);
        $content[] = 'for ($i = 0; $i < 10; $i++) {';
        foreach ($A as $cn) {
            $content[] = '$sum = '.$cn.'::test($sum);';
        }
        $content[] = '}';
        $content[] = 'echo "SUM=".$sum.\PHP_EOL;';
        $content[] = '';
        $content = \implode(\PHP_EOL, $content);
        \file_put_contents($this->dir.'/exec.php', $content);
    }
    
    private function createPhar()
    {
        $phar1 = new \Phar($this->dir.'/phar-none.phar');
        $phar1->buildFromDirectory($this->dir.'/classes');
        
        $phar2 = new \Phar($this->dir.'/phar-gz.phar');
        $phar2->buildFromDirectory($this->dir.'/classes');
        $phar2->compressFiles(\Phar::GZ);
        
        $phar3 = new \Phar($this->dir.'/phar-bz.phar');
        $phar3->buildFromDirectory($this->dir.'/classes');                
        $phar3->compressFiles(\Phar::BZ2);        
    }
    
    private function createRequire()
    {
        $content = ['<?php'];
        foreach ($this->uses as $cn) {
            $content[] = 'require_once(\''.$this->dir.'/classes/'.\str_replace('\\', '/', $cn).'.php\');';
        }
        $content[] = '';
        $content = \implode(\PHP_EOL, $content);
        \file_put_contents($this->dir.'/req.php', $content);
    }    
    
    private function createPharReq()
    {
        $content = ['<?php'];
        foreach ($this->uses as $cn) {
            $content[] = 'require_once(\'phar:///'.$this->dir.'/phar-none.phar/'.\str_replace('\\', '/', $cn).'.php\');';
        }
        $content[] = '';
        $content = \implode(\PHP_EOL, $content);
        \file_put_contents($this->dir.'/phar-req.php', $content);
    }     
   
    private function getRandomMethodName()
    {
        $s = 'qwertyuiopasdfghjklzxcvbnm';
        $l = array();
        for ($i = 0; $i < 7; $i++) {
            $l[] = $s[\mt_rand(0, 24)];
        }
        return \implode($l);
    }

    private function getRandomClassName()
    {
        return \ucfirst($this->getRandomMethodName());
    }    

    private $dir;
    
    private $classes;
    
    private $uses;
}

$dir = __DIR__.'/test';

if (file_exists($dir)) {
    echo 'dir already exists'.\PHP_EOL;
    exit();
}

$creator = new Creator($dir);
$creator->run();

