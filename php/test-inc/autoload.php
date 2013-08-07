<?php

class Autoloader
{
    public function __construct($dir)
    {
        $this->dir = $dir;
    }
    
    public function register()
    {
        \spl_autoload_register($this);
    }
    
    public function __invoke($classname)
    {
        $filename = $this->dir.'/'.\str_replace('\\', '/', $classname).'.php';
        if (!\file_exists($filename)) {
            return false;
        }
        require_once($filename);
        return true;
    }
}
