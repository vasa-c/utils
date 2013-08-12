#!/usr/bin/env php
<?php
/**
 * Skeleton for init.d-scripts
 *
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

class InitScript
{
    /**
     * @var string
     * @example "/usr"
     */
    protected $prefix = '';

    /**
     * Command for daemon start
     * @var string
     * @example "{prefix}/bin/cmd --config=/etc/cmd.conf"
     */
    protected $cmd = '';

    /**
     * Path to pid file
     * @var string
     * @example "{prefix}/var/run/cmd.pid"
     */
    protected $pidfile = '';

    /**
     * Constructor
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->loadPaths();
        $this->init();
    }

    /**
     * Display help
     */
    public function help()
    {
        $actions = implode('|', array_keys($this->actions));
        $this->outLine('Usage: '.$this->name.' ('.$actions.')');
    }

    /**
     * Run action
     *
     * @param string $action
     * @param array $args
     * @return boolean
     */
    public function run($action, $args)
    {
        if (!isset($this->actions[$action])) {
            $this->help();
            return false;
        }
        $method = $this->actions[$action];
        return $this->$method($args);
    }

    /**
     * Action: start
     *
     * @param array $args
     * @return boolean
     */
    protected function doStart($args)
    {
        if ($this->getPid()) {
            $this->outLine('Daemon is already running...');
            return false;
        }
        system($this->cmd);
        $pid = $this->getPid();
        if ($pid) {
            $this->outLine('Started (PID='.$pid.')');
            return true;
        } else {
            $this->outLine('Error starting (permission denied?)');
            return false;
        }
    }

    /**
     * Action: stop
     *
     * @param array $args
     * @return boolean
     */
    protected function doStop($args)
    {
        $pid = $this->getPid();
        if ($pid) {
            if (posix_kill($pid, 15)) {
                $this->outLine('Stopped...');
                return true;
            } else {
                $this->outLine('Error (permission denied?)');
                exit();
            }
        } else {
            $this->outLine('Daemon is not running.');
            return false;
        }
    }

    /**
     * Action: restart
     *
     * @param array $args
     * @return boolean
     */
    protected function doRestart($args)
    {
        if ($this->doStop($args)) {
            $this->outLine('Sleep (3s)...');
            sleep(3);
        }
        return $this->doStart($args);
    }

    /**
     * Action: restart
     *
     * @param array $args
     * @return boolean
     */
    protected function doStatus($args)
    {
        $pid = $this->getPid();
        if ($pid) {
            $date = date('d.m.Y, H:i:s', @filemtime($this->pidfile));
            $this->outLine('Daemon is running. Pid='.$pid.'. Starting: '.$date);
        } else {
            $this->outLine('Daemon is not running');
        }
    }

    /**
     * Get pid of daemon
     *
     * @return string
     */
    protected function getPid()
    {
        if (!is_file($this->pidfile)) {
            return null;
        }
        return @file_get_contents($this->pidfile);
    }

    /**
     * Load and check system paths
     */
    protected function loadPaths()
    {
        $this->cmd = str_replace('{prefix}', $this->prefix, $this->cmd);
        $this->pidfile = str_replace('{prefix}', $this->prefix, $this->pidfile);
        if (empty($this->cmd) || empty($this->pidfile)) {
            $this->outLine('Error: override cmd and pidfile in source');
            exit();
        }
    }

    /**
     * Init (for override, edit actions list and etc)
     */
    protected function init()
    {
        return true;
    }

    /**
     * Out line to stdout
     *
     * @param string $line
     */
    protected function outLine($line)
    {
        echo $line.PHP_EOL;
    }

    /**
     * Actions list (action => method name)
     *
     * @var array
     */
    protected $actions = array(
        'start' => 'doStart',
        'stop' => 'doStop',
        'restart' => 'doRestart',
        'status' => 'doStatus',
    );

    /**
     * Script name
     *
     * @var string
     */
    protected $name;
}

$args = $_SERVER['argv'];
$name = array_shift($args);

$script = new InitScript($name);

if (count($args) == 0) {
    $script->help();
    exit();
}

$action = array_shift($args);

$script->run($action, $args);

