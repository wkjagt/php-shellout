<?php


abstract class Connection
{
    protected $continue = true;

    // abstract public function listen();

    public function __construct()
    {
        declare(ticks = 1);

        pcntl_signal(SIGTERM, array($this, 'stop'));
        pcntl_signal(SIGINT, array($this, 'stop'));
        pcntl_signal(SIGHUP, array($this, 'stop'));
        
        pcntl_signal(SIGCHLD, SIG_IGN);
    }

    public function stop()
    {
        $this->debug("Stopping");
        $this->continue = false;
        $this->debug("Stopped");
    }

    protected function cont()
    {
        return $this->continue;
    }

    protected function display($i)
    {
        echo "$i\n";
    }

    protected function debug($m)
    {
        echo "$m\n";
    }

}